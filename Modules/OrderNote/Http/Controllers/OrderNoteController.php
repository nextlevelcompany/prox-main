<?php

namespace Modules\OrderNote\Http\Controllers;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\DocumentInput;
use App\CoreFacturalo\Requests\Web\Validation\DocumentValidation;
use App\CoreFacturalo\Template;
use App\Helpers\MailHelper;
use Exception;
use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\AttributeType;
use Modules\Catalog\Models\ChargeDiscountType;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\DocumentType;
use Modules\Catalog\Models\OperationType;
use Modules\Catalog\Models\PriceType;
use Modules\Catalog\Models\SystemIscType;
use Modules\Company\Models\Company;
use Modules\Company\Models\Configuration;
use Modules\Document\Http\Controllers\DocumentController;
use Modules\Document\Http\Requests\DocumentRequest;
use Modules\Document\Models\Document;
use Modules\Establishment\Models\Establishment;
use App\Models\Tenant\PaymentMethodType;
use Modules\Establishment\Models\Series;
use App\Traits\OfflineTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Item\Models\Item;
use Modules\OrderNote\Http\Requests\OrderNoteRequest;
use Modules\OrderNote\Http\Resources\OrderNoteCollection;
use Modules\OrderNote\Http\Resources\OrderNoteDocumentCollection;
use Modules\OrderNote\Http\Resources\OrderNoteResource;
use Modules\OrderNote\Models\OrderNote;
use Modules\OrderNote\Models\OrderNoteItem;
use Modules\Person\Models\Person;
use Modules\Quotation\Models\Quotation;
use Modules\SaleNote\Http\Controllers\SaleNoteController;
use Modules\SaleNote\Http\Requests\SaleNoteRequest;
use Modules\SaleNote\Models\SaleNote;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use App\Helpers\SearchItemHelper;
use App\Http\Controllers\Controller;
use Modules\Order\Mail\OrderNoteMail;


class OrderNoteController extends Controller
{
    use FinanceTrait;
    use StorageDocument;
    use OfflineTrait;

    protected $order_note;
    protected $company;

    public function index()
    {
        $company = Company::select('soap_type_id')->first();
        $soap_company = $company->soap_type_id;
        $configuration = Configuration::first();

        return view('tenant.order.order_notes.index', compact('soap_company', 'configuration'));
    }

    public function create()
    {

        return view('tenant.order.order_notes.form');

    }

    public function edit($id)
    {
        $resourceId = $id;
        $configuration = Configuration::first();
        return view('tenant.order.order_notes.form_edit', compact('resourceId', 'configuration'));
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha de emisión',
            'delivery_date' => 'Fecha de entrega',
            'user_name' => 'Vendedor'
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);

        $collect = new OrderNoteCollection($records->paginate(5));

        return $collect;
    }

    private function getRecords($request)
    {

        if ($request->column == 'user_name') {

            $records = OrderNote::whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->value}%");
            })
                ->whereTypeUser()
                ->latest();

        } else {

            $records = OrderNote::where($request->column, 'like', "%{$request->value}%")
                ->whereTypeUser()
                ->latest();

        }

        if ($request->state) {
            $records->where('state_type_id', $request->state);
        }

        if ($request->state_payment != '') {
            $ids = $records->pluck('id');
            $documents = Document::whereIn('order_note_id', $ids)
                ->where('total_canceled', $request->state_payment)
                ->pluck('order_note_id');
            $sale_note = SaleNote::whereIn('order_note_id', $ids)
                ->where('total_canceled', $request->state_payment)
                ->pluck('order_note_id');

            $union = $documents->union($sale_note);

            $records = OrderNote::whereIn('id', $union)
                ->whereTypeUser()
                ->latest();
        }

        return $records;
    }

    public function documents(Request $request)
    {
        $records = OrderNote::doesntHave('documents')
            ->doesntHave('sale_notes')
            ->where('state_type_id', '01')
            ->whereTypeUser()
            ->getDataFromRelatedTables()
            ->latest();

        return new OrderNoteDocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function document_tables()
    {
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $series = Series::where('establishment_id', $establishment->id)->get();
        // $document_types_invoice = DocumentType::whereIn('id', ['01', '03', '80'])->get();

        return compact('series', 'establishment');
    }


    public function generateDocuments(Request $request)
    {
        DB::connection('tenant')->transaction(function () use ($request) {
            foreach ($request->documents as $row) {
                if ($row['document_type_id'] === "80") {
                    app(SaleNoteController::class)->store(new SaleNoteRequest($row));
                } else {
                    $data_val = DocumentValidation::validation($row);
                    app(DocumentController::class)->store(new DocumentRequest(DocumentInput::set($data_val)));
                }
            }

        });

        return [
            'success' => true,
            'message' => 'Comprobantes generados'
        ];
    }

    public function searchCustomers(Request $request)
    {
        $customers = Person::where('number', 'like', "%{$request->input}%")
            ->orWhere('name', 'like', "%{$request->input}%")
            ->whereType('customers')->orderBy('name')
            ->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number . ' - ' . $row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code,
                    'address' => $row->address,
                ];
            });

        return compact('customers');
    }

    public function searchItemById($id)
    {
        $items = SearchItemHelper::getItemsToOrderNote(null, $id);
        return compact('items');

    }

    public function searchItems(Request $request)
    {
        $items = SearchItemHelper::getItemsToOrderNote($request);
        return compact('items');
    }

    public function tables()
    {
        $customers = $this->table('customers');
        $establishments = $this->generalGetEstablishmentForTables();
        // $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        $currency_types = func_get_table_currency_types();
        // $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_type_03_filter = config('tenant.document_type_03_filter');
        $payment_method_types = PaymentMethodType::orderBy('id', 'desc')->get();
        $payment_destinations = $this->getPaymentDestinations();
        $series = Series::query()
            ->where('document_type_id', 'U6')
            ->get()
            ->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'number' => $row->number,
                    'establishment_id' => $row->establishment_id
                ];
            });

        return compact('customers', 'establishments', 'currency_types', 'discount_types',
            'charge_types', 'company', 'document_type_03_filter', 'payment_method_types', 'payment_destinations',
            'series');
    }

    public function table($table)
    {
        switch ($table) {
            case 'customers':

                $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number . ' - ' . $row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code,
                        'address' => $row->address,
                    ];
                });
                return $customers;

                break;

            case 'items':

                $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

                $items = Item::orderBy('description')->whereIsActive()->whereNotIsSet()
                    // ->with(['warehouses' => function($query) use($warehouse){
                    //     return $query->where('warehouse_id', $warehouse->id);
                    // }])
                    ->get()->transform(function ($row) use ($warehouse) {
                        /** @var Item $row */
                        return $row->getDataToItemModal($warehouse, true, true);
                        $full_description = $this->getFullDescription($row);
                        // $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                        $lots = $row->item_lots->where('has_sale', false);
                        return [
                            'id' => $row->id,
                            'full_description' => $full_description,
                            'description' => $row->description,
                            'currency_type_id' => $row->currency_type_id,
                            'currency_type_symbol' => $row->currency_type->symbol,
                            'sale_unit_price' => $row->sale_unit_price,
                            'purchase_unit_price' => $row->purchase_unit_price,
                            'unit_type_id' => $row->unit_type_id,
                            'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                            'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                            'is_set' => (bool)$row->is_set,
                            'has_igv' => (bool)$row->has_igv,
                            'calculate_quantity' => (bool)$row->calculate_quantity,
                            'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => "{$row->description}",
                                    'item_id' => $row->item_id,
                                    'unit_type_id' => $row->unit_type_id,
                                    'quantity_unit' => $row->quantity_unit,
                                    'price1' => $row->price1,
                                    'price2' => $row->price2,
                                    'price3' => $row->price3,
                                    'price_default' => $row->price_default,
                                ];
                            }),
                            'warehouses' => collect($row->warehouses)->transform(function ($row) use ($warehouse) {
                                return [
                                    'warehouse_id' => $row->warehouse->id,
                                    'warehouse_description' => $row->warehouse->description,
                                    'stock' => $row->stock,
                                    'checked' => ($row->warehouse_id == $warehouse->id) ? true : false,
                                ];
                            }),
                            'lots' => $lots->transform(function ($row) {
                                return [
                                    'id' => $row->id,
                                    'series' => $row->series,
                                    'date' => $row->date,
                                    'item_id' => $row->item_id,
                                    'warehouse_id' => $row->warehouse_id,
                                    'has_sale' => (bool)$row->has_sale,
                                    'lot_code' => ($row->item_loteable_type) ?
                                        (isset($row->item_loteable->lot_code) ?
                                            $row->item_loteable->lot_code :
                                            null) :
                                        null
                                ];
                            })->values(),
                            'series_enabled' => (bool)$row->series_enabled,
                        ];
                    });
                return $items;

                break;
            default:
                return [];

                break;
        }
    }

    public function getFullDescription($row)
    {

        $desc = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
        $category = ($row->category) ? " - {$row->category->name}" : "";
        $brand = ($row->brand) ? " - {$row->brand->name}" : "";

        $desc = "{$desc} {$category} {$brand}";

        return $desc;
    }

    public function option_tables()
    {
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $series = Series::where('establishment_id', $establishment->id)->get();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03', '80'])->get();
        $payment_method_types = PaymentMethodType::all();
        $payment_destinations = $this->getPaymentDestinations();

        return compact('series', 'document_types_invoice', 'payment_method_types', 'payment_destinations');
    }

    public function item_tables()
    {
        // $items = $this->table('items');
        $items = SearchItemHelper::getItemsToOrderNote();
        $categories = [];
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $is_client = $this->getIsClient();

        return compact(
            'items',
            'categories',
            'affectation_igv_types',
            'system_isc_types',
            'price_types',
            'discount_types',
            'charge_types',
            'attribute_types',
            'operation_types',
            'is_client'
        );
    }

    public function record($id)
    {
        $record = new OrderNoteResource(OrderNote::findOrFail($id));

        return $record;
    }

    public function record2($id)
    {
        $record = new OrderNoteResource(OrderNote::findOrFail($id));

        return $record;
    }

    public function store(OrderNoteRequest $request)
    {
        $data = $this->mergeArray($request);
        /* @todo Deberia pasarse a facturalo para tenerlo como standar */
        DB::connection('tenant')->transaction(function () use ($data) {
            $this->order_note = OrderNote::create($data);

            foreach ($data['items'] as $row) {
                $this->generalSetIdLoteSelectedToItem($row);
                $this->order_note->items()->create($row);
            }

//            $this->setFilename();
            $this->createPdf($this->order_note, "a4", $this->order_note->filename);

        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->order_note->id,
                'external_id' => $this->order_note->external_id,
                'number_full' => $this->order_note->number_full,
                'filename' => $this->order_note->filename,
                'print_ticket' => $this->order_note->getUrlPrintPdf('ticket'),
            ],
        ];
    }

    /**
     * Recibe un Request y lo transforma en array para poder ser procesado por el modelo.
     *
     * @param Request|null $request
     * @param null $order_note
     *
     * @return array
     */
    public function mergeArray(Request $request = null, $order_note = null)
    {
        $this->company = Company::active();
        $data = $request->toArray();
        $values = [
            'user_id' => ($order_note) ? $order_note->user_id : auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => PersonInput::set($request->customer_id),
            'establishment' => EstablishmentInput::set($request->establishment_id),
            'soap_type_id' => $this->company->soap_type_id,
            'state_type_id' => '01',
            'type' => 'order-notes',
        ];

        return array_merge($data, $values);
    }

//    private function setFilename()
//    {
//
//        $name = [$this->order_note->prefix, $this->order_note->id, date('Ymd')];
//        $this->order_note->filename = join('-', $name);
//        $this->order_note->save();
//
//    }

    /**
     * @param null $order_note
     * @param null $format_pdf
     * @param null $filename
     *
     * @throws MpdfException
     */
    public function createPdf($order_note = null, $format_pdf = null, $filename = null)
    {
        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $document = ($order_note != null) ? $order_note : $this->order_note;
        $company = ($this->company != null) ? $this->company : Company::active();
        $filename = ($filename != null) ? $filename : $this->order_note->filename;

        // $base_template = config('tenant.pdf_template');
        $base_template = Establishment::find($document->establishment_id)->template_pdf;

        $html = $template->pdf($base_template, "order_note", $company, $document, $format_pdf);

        if ($format_pdf === 'ticket' or $format_pdf === 'ticket_80' or $format_pdf === 'ticket_58') {

            $width = 78;
            $pdf_margin_top = 2;
            $pdf_margin_right = 5;
            $pdf_margin_bottom = 0;
            $pdf_margin_left = 5;
            if (config('tenant.enabled_template_ticket_80')) $width = 76;

            if ($format_pdf === 'ticket_58') {
                $width = 58;
                $pdf_margin_top = 1;
                $pdf_margin_right = 1;
                $pdf_margin_bottom = 0;
                $pdf_margin_left = 1;
            }

            $company_name = (strlen($company->name) / 20) * 10;
            $company_address = (strlen($document->establishment->address) / 30) * 10;
            $company_number = $document->establishment->telephone != '' ? '10' : '0';
            $customer_name = strlen($document->customer->name) > '25' ? '10' : '0';
            $customer_address = (strlen($document->customer->address) / 200) * 10;
            $p_order = $document->purchase_order != '' ? '10' : '0';

            $total_exportation = $document->total_exportation != '' ? '10' : '0';
            $total_free = $document->total_free != '' ? '10' : '0';
            $total_unaffected = $document->total_unaffected != '' ? '10' : '0';
            $total_exonerated = $document->total_exonerated != '' ? '10' : '0';
            $total_taxed = $document->total_taxed != '' ? '10' : '0';
            $quantity_rows = count($document->items);
            $discount_global = 0;
            foreach ($document->items as $it) {
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends = $document->legends != '' ? '10' : '0';
            $payments_quantity = count($document->getCollectPrepayments());

            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    $width,
                    120 +
                    ($quantity_rows * 8) +
                    ($payments_quantity * 8) +
                    ($discount_global * 3) +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $total_exonerated +
                    $total_taxed],
                'margin_top' => $pdf_margin_top,
                'margin_right' => $pdf_margin_right,
                'margin_bottom' => $pdf_margin_bottom,
                'margin_left' => $pdf_margin_left,
            ]);
        } else {
            if ($format_pdf === 'a5') {

                $company_name = (strlen($company->name) / 20) * 10;
                $company_address = (strlen($document->establishment->address) / 30) * 10;
                $company_number = $document->establishment->telephone != '' ? '10' : '0';
                $customer_name = strlen($document->customer->name) > '25' ? '10' : '0';
                $customer_address = (strlen($document->customer->address) / 200) * 10;
                $p_order = $document->purchase_order != '' ? '10' : '0';

                $total_exportation = $document->total_exportation != '' ? '10' : '0';
                $total_free = $document->total_free != '' ? '10' : '0';
                $total_unaffected = $document->total_unaffected != '' ? '10' : '0';
                $total_exonerated = $document->total_exonerated != '' ? '10' : '0';
                $total_taxed = $document->total_taxed != '' ? '10' : '0';
                $quantity_rows = count($document->items);
                $discount_global = 0;
                foreach ($document->items as $it) {
                    if ($it->discounts) {
                        $discount_global = $discount_global + 1;
                    }
                }
                $legends = $document->legends != '' ? '10' : '0';


                $alto = ($quantity_rows * 8) +
                    ($discount_global * 3) +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $total_exonerated +
                    $total_taxed;
                $diferencia = 148 - (float)$alto;

                $pdf = new Mpdf([
                    'mode' => 'utf-8',
                    'format' => [
                        210,
                        $diferencia + $alto,
                    ],
                    'margin_top' => 2,
                    'margin_right' => 5,
                    'margin_bottom' => 0,
                    'margin_left' => 5,
                ]);


            } else {

                $pdf_font_regular = config('tenant.pdf_name_regular');
                $pdf_font_bold = config('tenant.pdf_name_bold');

                if ($pdf_font_regular != false) {
                    $defaultConfig = (new ConfigVariables())->getDefaults();
                    $fontDirs = $defaultConfig['fontDir'];

                    $defaultFontConfig = (new FontVariables())->getDefaults();
                    $fontData = $defaultFontConfig['fontdata'];

                    $pdf = new Mpdf([
                        'fontDir' => array_merge($fontDirs, [
                            app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                                DIRECTORY_SEPARATOR . 'pdf' .
                                DIRECTORY_SEPARATOR . $base_template .
                                DIRECTORY_SEPARATOR . 'font'),
                        ]),
                        'fontdata' => $fontData + [
                                'custom_bold' => [
                                    'R' => $pdf_font_bold . '.ttf',
                                ],
                                'custom_regular' => [
                                    'R' => $pdf_font_regular . '.ttf',
                                ],
                            ],
                    ]);
                }
            }
        }

        $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
            DIRECTORY_SEPARATOR . 'pdf' .
            DIRECTORY_SEPARATOR . $base_template .
            DIRECTORY_SEPARATOR . 'style.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        if ($format_pdf != 'ticket') {
            if (config('tenant.pdf_template_footer')) {
                $html_footer = $template->pdfFooter($base_template, $this->order_note);
                $pdf->SetHTMLFooter($html_footer);
            }
            //$html_footer = $template->pdfFooter();
            //$pdf->SetHTMLFooter($html_footer);
        }

        $this->uploadFile($filename, $pdf->output('', 'S'), 'order_note');
    }

    public function uploadFile($filename, $file_content, $file_type)
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

    public function update(OrderNoteRequest $request)
    {

        DB::connection('tenant')->transaction(function () use ($request) {


            $this->order_note = OrderNote::firstOrNew(['id' => $request['id']]);

            // $data = $this->mergeData($request, $this->order_note);
            $data = $this->mergeArray($request, $this->order_note);


            $this->order_note->fill($data);
            //$this->order_note->items()->delete();

            foreach ($request['items'] as $row) {

                // $this->order_note->items()->create($row);
                // $item_id = isset($row['id']) ? $row['id'] : null;
                $item_id = $this->getRowIdItem($row);
                $order_note_item = OrderNoteItem::firstOrNew(['id' => $item_id]);
                $this->generalSetIdLoteSelectedToItem($row);
                $order_note_item->fill($row);
                $order_note_item->order_note_id = $this->order_note->id;
                $order_note_item->save();

            }

//            $this->setFilename();
        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->order_note->id,
            ],
        ];

    }


    /**
     *
     * Obtener id de la fila al editar pedido
     *
     * @param array $row
     * @return int|null
     */
    private function getRowIdItem($row)
    {
        $row_id = null;

        if (isset($row['id'])) {
            $row_id = $row['id'];
        } else {
            if (isset($row['record_id'])) $row_id = $row['record_id'];
        }

        return $row_id;
    }


    public function destroy_order_note_item($id)
    {

        DB::connection('tenant')->transaction(function () use ($id) {

            $item = OrderNoteItem::findOrFail($id);
            $item->delete();

        });

        return [
            'success' => true,
            'message' => 'Item eliminado'
        ];
    }

    public function duplicate(Request $request)
    {
        // return $request->id;
        $obj = OrderNote::find($request->id);
        $this->order_note = $obj->replicate();
        $this->order_note->external_id = Str::uuid()->toString();
        $this->order_note->state_type_id = '01';
        $this->order_note->save();

        foreach ($obj->items as $row) {
            $new = $row->replicate();
            $new->order_note_id = $this->order_note->id;
            $new->save();
        }

//        $this->setFilename();

        return [
            'success' => true,
            'data' => [
                'id' => $this->order_note->id,
            ],
        ];

    }

    public function voided($id)
    {
        DB::connection('tenant')->transaction(function () use ($id) {
            $obj = OrderNote::find($id);
            $obj->VoidOrderNote();
            $obj->update();
        });

        return [
            'success' => true,
            'message' => 'Pedido anulado con éxito'
        ];
    }

    /**
     * @param      $inputs
     * @param null $order_note
     *
     * @return mixed
     * @deprecated  use mergeArray instead
     */
    public function mergeData($inputs, $order_note = null)
    {

        $this->company = Company::active();

        $values = [
            'user_id' => ($order_note) ? $order_note->user_id : auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => PersonInput::set($inputs['customer_id']),
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id,
            'state_type_id' => '01'
        ];

        $inputs->merge($values);

        return $inputs->all();
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function searchCustomerById($id)
    {

        return $this->searchClientById($id);
    }

    public function download($external_id, $format)
    {
        $order_note = OrderNote::where('external_id', $external_id)->first();

        if (!$order_note) throw new Exception("El código {$external_id} es inválido, no se encontro el pedido relacionado");

        $this->reloadPDF($order_note, $format, $order_note->filename);

        return $this->downloadStorage($order_note->filename, 'order_note');
    }

    private function reloadPDF($order_note, $format, $filename)
    {
        $this->createPdf($order_note, $format, $filename);
    }

    public function toPrint($external_id, $format)
    {
        $order_note = OrderNote::where('external_id', $external_id)->first();

        if (!$order_note) throw new Exception("El código {$external_id} es inválido, no se encontro el pedido relacionado");

        $this->reloadPDF($order_note, $format, $order_note->filename);
        $temp = tempnam(sys_get_temp_dir(), 'order_note');

        file_put_contents($temp, $this->getStorage($order_note->filename, 'order_note'));

        /*
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$order_note->filename.'"'
        ];
        */

        return response()->file($temp, $this->generalPdfResponseFileHeaders($order_note->filename));
    }

    public function email(Request $request)
    {

        $client = Person::find($request->customer_id);
        $order_note = OrderNote::find($request->id);
        $customer_email = $request->input('customer_email');

        // $this->reloadPDF($order_note, "a4", $order_note->filename);
        $email = $customer_email;
        $mailable = new OrderNoteMail($client, $order_note);
        $id = (int)$order_note->id;
        $model = __FILE__ . ";;" . __LINE__;
        $sendIt = MailHelper::SendMail($email, $mailable, $id, $model);

        return [
            'success' => true
        ];
    }

    public function getQuotationToOrderNote(Quotation $id)
    {
        $company = Company::query()->first();
        $configuration = Configuration::query()->first();

        return $id->getCollectionData($company, $configuration, true);
    }

    /**
     * @return OrderNote
     */
    public function getOrderNote()
    {
        return $this->order_note;
    }

    public function setOrderNote(OrderNote $order_note)
    {
        $this->order_note = $order_note;
        return $this;
    }

    public function updateState(Request $request)
    {
        DB::connection('tenant')->transaction(function () use ($request) {
            $order_note = OrderNote::find($request->id);
            $order_note->state_type_id = $request->state_type_id;
            $order_note->save();
        });

        return [
            'success' => true,
            'message' => 'Pedido actualizado con éxito'
        ];
    }
}
