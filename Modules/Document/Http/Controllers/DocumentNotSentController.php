<?php

namespace Modules\Document\Http\Controllers;

use App\Helpers\SearchItemHelper;
use Modules\Establishment\Models\Series;
use Modules\Item\Http\Resources\ItemLotCollection;
use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentItem;
use Modules\Document\Http\Resources\DocumentNotSentCollection;
use Modules\Catalog\Models\DocumentType;
use Modules\Establishment\Models\Establishment;
use Illuminate\Support\Facades\Storage;
use Modules\Person\Models\Person;
use Modules\Company\Models\StateType;
use App\Traits\OfflineTrait;
use Modules\Inventory\Models\Warehouse as ModuleWarehouse;
use Modules\Document\Traits\SearchTrait;
use Modules\Finance\Helpers\UploadFileHelper;
use Modules\Document\Helpers\ConsultCdr;
use Modules\Item\Models\ItemLot;
use Modules\SaleNote\Models\SaleNoteItem;
use Illuminate\Database\Eloquent\Builder;

class DocumentNotSentController extends Controller
{
    use OfflineTrait, SearchTrait;

    public function index()
    {

        $is_client = $this->getIsClient();

        return view('tenant.documents.documents.not_sent', compact('is_client'));
    }

    public function records(Request $request)
    {

        $records = $this->getRecords($request);

        return new DocumentNotSentCollection($records->paginate(config('tenant.items_per_page')));

    }

    public function getRecords($request)
    {

        /** @var User $user */
        $user = \Auth::user();

        $d_end = $request->d_end;
        $d_start = $request->d_start;
        $date_of_issue = $request->date_of_issue;
        $document_type_id = $request->document_type_id;
        $number = $request->number;
        $series = $request->series;
        $state_type_id = $request->state_type_id;
        $pending_payment = ($request->pending_payment == "true") ? true : false;
        $customer_id = $request->customer_id;

        $records = Document::
        where('series', 'like', '%' . $series . '%')
            ->where('number', 'like', '%' . $number . '%')
            ->where('state_type_id', 'like', '%' . $state_type_id . '%')
            ->where('document_type_id', 'like', '%' . $document_type_id . '%')
            ->whereNotSent()//->whereTypeUser()
        ;

        if ($d_start && $d_end) {
            $records->whereBetween('date_of_issue', [$d_start, $d_end]);
        } else {
            $records->where('date_of_issue', 'like', '%' . $date_of_issue . '%');
        }
        $records->latest();
        if ($pending_payment) {
            $records = $records->where('total_canceled', false);
        }

        if ($customer_id) {
            $records = $records->where('customer_id', $customer_id);
        }

        return $records;

    }

    public function data_table()
    {

        $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->number . ' - ' . $row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        $document_types = DocumentType::whereIn('id', ['01', '03', '07', '08'])->get();
        $series = Series::whereIn('document_type_id', ['01', '03', '07', '08'])->get();
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        $state_types = StateType::get();

        return compact('customers', 'document_types', 'series', 'establishments', 'state_types');

    }


    public function upload(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'jpg,jpeg,png,gif,svg');

        if (!$validate_upload['success']) {
            return $validate_upload;
        }

        if ($request->hasFile('file')) {
            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            return $this->upload_image($new_request);
        }
        return [
            'success' => false,
            'message' => __('app.actions.upload.error'),
        ];
    }

    function upload_image($request)
    {
        $file = $request['file'];
        $type = $request['type'];

        $temp = tempnam(sys_get_temp_dir(), $type);
        file_put_contents($temp, file_get_contents($file));

        $mime = mime_content_type($temp);
        $data = file_get_contents($temp);

        return [
            'success' => true,
            'data' => [
                'filename' => $file->getClientOriginalName(),
                'temp_path' => $temp,
                'temp_image' => 'data:' . $mime . ';base64,' . base64_encode($data)
            ]
        ];
    }

    public function dataTableCustomers(Request $request)
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
                ];
            });

        return compact('customers');
    }

    public function savePayConstancy(Request $request)
    {
        $document = Document::findOrFail($request->id);

        $detraction = $document->detraction;
        $detraction->pay_constancy = $request->pay_constancy;


        if ($request->upload_image_pay_constancy) {
            //hacer proceso de carga de imagen
            $image_pay_constancy = $request->upload_image_pay_constancy;
            $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'image_detractions' . DIRECTORY_SEPARATOR;

            $file_name_old = $image_pay_constancy['image'];

            UploadFileHelper::checkIfValidFile($file_name_old, $image_pay_constancy['temp_path'], true);

            $file_name_old_array = explode('.', $file_name_old);
            $file_content = file_get_contents($image_pay_constancy['temp_path']);
            $datenow = date('YmdHis');
            $file_name = $detraction->detraction_type_id . '-' . $detraction->bank_account . '-' . $datenow . '.' . $file_name_old_array[1];
            Storage::put($directory . $file_name, $file_content);
            $set_image_pay_constancy = $file_name;
            $detraction->image_pay_constancy = $set_image_pay_constancy;

        }

        // dd($detraction, $request->upload_image_pay_constancy['temp_path']);
        $document->detraction = $detraction;
        $document->save();

        return [
            'success' => true,
            'message' => 'Constancia de pago guardada',
        ];
    }

    public function prepayments($type)
    {
        $prepayment_documents = Document::whereHasPrepayment()->whereAffectationTypePrepayment($type)->get()->transform(function ($row) {

            $total = round($row->pending_amount_prepayment, 2);
            $amount = ($row->affectation_type_prepayment == '10') ? round($total / 1.18, 2) : $total;

            return [
                'id' => $row->id,
                'description' => $row->series . '-' . $row->number,
                'series' => $row->series,
                'number' => $row->number,
                'document_type_id' => ($row->document_type_id == '01') ? '02' : '03',
                // 'amount' => $row->total_value,
                // 'total' => $row->total,
                'amount' => $amount,
                'total' => $total,

            ];
        });

        return $prepayment_documents;
    }

    public function searchItems(Request $request)
    {
        $items = SearchItemHelper::getItemsToDocuments($request);

        return compact('items');
    }

    public function searchLots(Request $request)
    {
        $records = ItemLot::where('series', 'like', "%{$request->input}%");
        $sale_note_item_id = $request->has('sale_note_item_id') ? $request->sale_note_item_id : null;

        if ($request->document_item_id) {
            //proccess credit note
            $document_item = DocumentItem::query()
                ->findOrFail($request->document_item_id);
            /** @var array $lots */
            $lots = $document_item->item->lots;
            $records
                ->whereIn('id', collect($lots)->pluck('id')->toArray())
                ->where('has_sale', true)
                ->latest();

        } else if ($sale_note_item_id) {
            $records = $this->getRecordsForSaleNoteItem($records, $sale_note_item_id, $request);
        } else {
            $warehouse = ModuleWarehouse::query()
                ->select('id')
                ->where('establishment_id', auth()->user()->establishment_id)
                ->first();
            $records
                ->where('item_id', $request->item_id)
                ->where('has_sale', false)
                ->where('warehouse_id', $warehouse->id)
                ->latest();
        }

//        return new ItemLotCollection($records->get());
        return new ItemLotCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function searchItemLots(Request $request)
    {
        $query = ItemLot::query()
            ->where('series', 'like', "%{$request->input('input')}%");
        $sale_note_item_id = $request->has('sale_note_item_id') ? $request->input('sale_note_item_id') : null;
        $document_item_id = $request->has('document_item_id') ? $request->input('document_item_id') : null;
        $warehouse_id = $request->has('warehouse_id') ? $request->input('warehouse_id') : null;

        if ($document_item_id) {
            //proccess credit note
            $document_item = DocumentItem::query()
                ->findOrFail($document_item_id);
            /** @var array $lots */
            $lots = $document_item->item->lots;
            $query->whereIn('id', collect($lots)->pluck('id')->toArray())
                ->where('has_sale', true)
                ->latest();

        } else if ($sale_note_item_id) {
            $query = $this->getRecordsForSaleNoteItem($query, $sale_note_item_id, $request);
        } else {
            if (is_null($warehouse_id)) {
                $warehouse = ModuleWarehouse::query()
                    ->select('id')
                    ->where('establishment_id', auth()->user()->establishment_id)
                    ->first();
                $warehouse_id = $warehouse->id;
            }
            $query
                ->where('item_id', $request->input('item_id'))
                ->where('has_sale', false)
                ->where('warehouse_id', $warehouse_id)
                ->latest();
        }

        return new ItemLotCollection($query->paginate(config('tenant.items_per_page')));
    }


    /**
     *
     * Obtener series disponibles y vendidas en la nota de venta
     * Usado para edicion de item en nv
     *
     * @param ItemLot $records
     * @param int $sale_note_item_id
     * @param Request $request
     * @return Builder
     */
    public function getRecordsForSaleNoteItem($records, $sale_note_item_id, $request)
    {
        // obtener series disponibles
        $records->whereAvailableItemLot($request->item_id)->latest();

        // obtener series vendidas en la nv
        $sale_note_item = SaleNoteItem::findOrFail($sale_note_item_id);
        $lots = $sale_note_item->item->lots;

        $sale_lots = ItemLot::whereIn('id', collect($lots)->pluck('id')->toArray())->where('has_sale', true)->latest();

        return $sale_lots->union($records);
    }


    public function regularizeLots(Request $request)
    {

        $document_item = DocumentItem::findOrFail($request->document_item_id);

        return ItemLot::where('series', 'like', "%{$request->input}%")
            ->whereIn('id', collect($document_item->item->lots)->pluck('id')->toArray())
            ->where('has_sale', true)
            ->get();


    }


    public function searchItemById($id)
    {
        // $items = SearchItemHelper::searchByIdToModal($id);
        $items = SearchItemHelper::getItemsToDocuments(null, $id);
        return compact('items');
    }


    public function consultCdr($document_id)
    {

        $document = Document::find($document_id);

        return (new ConsultCdr)->search($document);

    }


    /**
     *
     * Forzar el envio por resumen a una boleta enviada de forma individual
     *
     * @param Request $request
     * @return array
     */
    public function forceSendBySummary(Request $request)
    {
        $document = Document::findOrFail($request->id);
        $document->force_send_by_summary = true;
        $document->ticket_single_shipment = false;
        $document->update();

        return [
            'success' => true,
            'message' => 'Se habilitó el comprobante para enviarlo por resumen'
        ];
    }

}
