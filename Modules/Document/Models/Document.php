<?php

namespace Modules\Document\Models;

use App\CoreFacturalo\Helpers\QrCode\QrCodeGenerate;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\PaymentCondition;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\DocumentType;
use App\Models\Tenant\ModelTenant;
use App\Traits\SellerIdTrait;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Modules\BusinessTurn\Models\DocumentHotel;
use Modules\BusinessTurn\Models\DocumentTransport;
use Modules\Company\Models\Company;
use Modules\Company\Models\SoapType;
use Modules\Company\Models\StateType;
use Modules\Dispatch\Models\Dispatch;
use Modules\Establishment\Models\Establishment;
use Modules\Inventory\Models\InventoryKardex;
use Modules\Item\Models\WebPlatform;
use Modules\OrderNote\Models\OrderNote;
use Modules\Person\Models\Person;
use Modules\Quotation\Models\Quotation;
use Modules\Pos\Models\Tip;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Models\Agent;
use Modules\SaleNote\Models\SaleNote;
use Modules\Summary\Models\SummaryDocument;
use Modules\TechnicalService\Models\TechnicalService;
use Modules\User\Models\User;
use App\Models\Tenant\PaymentMethodType;
use Modules\Item\Models\Item;
use Modules\Document\Http\Controllers\DownloadController;
use Modules\GlobalFactoring\Models\ApiState;


class Document extends ModelTenant
{
    use SellerIdTrait;

    public const DOCUMENT_TYPE_TICKET = '03';

    public const GROUP_INVOICE = '01';

    public const GROUP_TICKET = '02';

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'group_id',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'currency_type_id',
        'purchase_order',
        'grade',
        'section',
        'quotation_id',
        'exchange_rate_sale',
        'total_prepayment',
        'total_discount',
        'total_charge',
        'total_exportation',
        'total_free',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_base_isc',
        'total_isc',
        'total_base_other_taxes',
        'total_other_taxes',
        'total_taxes',
        'total_value',
        'total',
        'charges',
        'discounts',
        'prepayments',
        'guides',
        'related',
        'perception',
        'detraction',
        'legends',
        'additional_information',
        'additional_data',
        'filename',
        'hash',
        'qr',
        'has_xml',
        'has_pdf',
        'has_cdr',
        'has_prepayment',
        'affectation_type_prepayment',
        'data_json',
        'send_server',
        'shipping_status',
        'sunat_shipping_status',
        'query_status',
        'total_plastic_bag_taxes',
        'sale_note_id',
        'success_shipping_status',
        'success_sunat_shipping_status',
        'success_query_status',
        'plate_number',
        'total_canceled',
        'order_note_id',
        'soap_shipping_response',
        'pending_amount_prepayment',
        'payment_method_type_id',
        'regularize_shipping',
        'response_regularize_shipping',
        'seller_id',
        'reference_data',
        'terms_condition',
        'payment_condition_id',
        'is_editable',
        'dispatch_id',
        'subtotal',
        'total_igv_free',
        'technical_service_id',
        // 'pending_amount_detraction',
        'total_pending_payment', //usado para detracciones - retenciones
        'retention',
        'user_rel_subscription_plan_id',
        'automatic_date_of_issue',
        'type_period',
        'quantity_period',
        'enabled_concurrency',
        'apply_concurrency',

        'send_to_pse',
        'response_signature_pse',
        'response_send_cdr_pse',

        'sale_notes_relateds', //generar cpe desde multiples notas de venta
        'unique_filename', //registra nombre de archivo unico (campo validador para evitar duplicidad)

        'ticket_single_shipment',
        'point_system',
        'point_system_data',
        'folio',
        'agent_id',
        'force_send_by_summary',
        'dispatch_ticket_pdf',
        'collect_api_state_id'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'user_rel_subscription_plan_id' => 'int',
        'quantity_period' => 'int',
        'enabled_concurrency' => 'bool',
        'apply_concurrency' => 'bool',
        'send_to_pse' => 'bool',
        'total' => 'float',
        'ticket_single_shipment' => 'bool',
        'point_system' => 'bool',
        'force_send_by_summary' => 'bool',
        'dispatch_ticket_pdf' => 'bool',
        'collect_api_state_id' => 'int',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            self::adjustSellerIdField($model);
        });

    }

    public function getAdditionalDataAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setAdditionalDataAttribute($value)
    {
        $this->attributes['additional_data'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * Devuelve el ultimo numero por serie, si no existe devielve 0
     *
     * @param string $serie
     *
     * @return int
     */
    public static function getLastNumberBySerie($serie)
    {
        $t = Document::where('series', $serie)->select('number')->orderby('number', 'DESC')->first();
        if (!empty($t)) {
            return $t->number;
        }
        return 0;
    }

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getChargesAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getPrepaymentsAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getGuidesAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setGuidesAttribute($value)
    {
        $this->attributes['guides'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getRelatedAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getPerceptionAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getDetractionAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getDataJsonAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setDataJsonAttribute($value)
    {
        $this->attributes['data_json'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getSoapShippingResponseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setSoapShippingResponseAttribute($value)
    {
        $this->attributes['soap_shipping_response'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getResponseRegularizeShippingAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setResponseRegularizeShippingAttribute($value)
    {
        $this->attributes['response_regularize_shipping'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getRetentionAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setRetentionAttribute($value)
    {
        $this->attributes['retention'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getPointSystemDataAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setPointSystemDataAttribute($value)
    {
        $this->attributes['point_system_data'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getAdditionalInformationAttribute($value)
    {
        $arr = explode('|', $value);
        return $arr;
    }

    /**
     * @return BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * @return BelongsTo
     */
    public function relation_establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    /**
     * @return BelongsTo
     */
    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    /**
     * @return BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return BelongsTo
     */
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function getCompanyAttribute()
    {
        return Company::first();
    }

    /**
     * @return HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * @return HasOne
     */
    public function note()
    {
        return $this->hasOne(Note::class);
    }

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    /**
     * @return HasMany
     */
    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(DocumentPayment::class);
    }

    /**
     * @return HasMany
     */
    public function fee()
    {
        return $this->hasMany(DocumentFee::class);
    }

    /**
     * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
     * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
     * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
     *
     * @return MorphMany
     */
    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    /**
     * @return BelongsTo
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * @return BelongsTo
     */
    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }

    /**
     * @return BelongsTo
     */
    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_id');
    }

    /**
     * @return HasOne
     */
    public function hotel()
    {
        return $this->hasOne(DocumentHotel::class);
    }

    /**
     * @return HasOne
     */
    public function transport()
    {
        return $this->hasOne(DocumentTransport::class);
    }

    /**
     * @return string
     */
    public function getNumberFullAttribute()
    {
        return $this->series . '-' . $this->number;
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    /**
     * @return string
     */
    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    /**
     * @return string
     */
    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }

    /**
     * @param $query
     *
     * @return null
     */
    public function scopeWhereTypeUser($query, $params = [])
    {
        /** @var User $user */
        //$user_id = null;

        if (isset($params['user_id'])) {
            $user_id = (int)$params['user_id'];
            $user = User::find($user_id);
            if (!$user) {
                $user = new User();
            }
        } else {
            $user = auth()->user();
        }

        return ($user->type === 'admin') ? null : $query->where('user_id', $user->id)->orWhere('seller_id', $user->id)->latest();
        // return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereNotSent($query)
    {
        return $query->whereIn('state_type_id', ['01', '03'])->where('date_of_issue', '<=', date('Y-m-d'));
    }

    /**
     * @return HasMany
     */
    public function affected_documents()
    {
        return $this->hasMany(Note::class, 'affected_document_id');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereHasPrepayment($query)
    {
        return $query->where([['has_prepayment', true], ['was_deducted_prepayment', false], ['state_type_id', '05']]);
    }

    /**
     * @return HasMany
     */
    public function reference_guides()
    {
        return $this->hasMany(Dispatch::class, 'reference_document_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function summary_document()
    {
        return $this->hasOne(SummaryDocument::class);
    }

    /**
     * @param $query
     * @param $type
     *
     * @return mixed
     */
    public function scopeWhereAffectationTypePrepayment($query, $type)
    {
        return $query->where('affectation_type_prepayment', $type);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
    }

    /**
     * @return BelongsTo
     */
    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereRegularizeShipping($query)
    {
        return $query->where('state_type_id', '01')->where('regularize_shipping', true);
    }

    /**
     * @return BelongsTo
     */
    public function order_note()
    {
        return $this->belongsTo(OrderNote::class);
    }

    /**
     * @return BelongsTo
     */
    public function technical_service()
    {
        return $this->belongsTo(TechnicalService::class);
    }

    /**
     * @return BelongsTo
     */
    public function payment_condition()
    {
        return $this->belongsTo(PaymentCondition::class);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function getIsEditableAttribute($value)
    {
        return $value ? true : false;
    }

    /**
     * Evalua si es posible borrarlo basado en las condiciones:
     * regularize_shipping y response_regularize_shipping no este vacio
     * El documento este replicado  en series y numero
     *
     * @return bool
     */
    public function canDelete()
    {
        if (!empty($this->regularize_shipping) &&
            !empty($this->response_regularize_shipping)) {
            $duplicated = self::where([
                'series' => $this->series,
                'number' => $this->number,
            ])->where('id', '!=', $this->id)->first();
            if (!empty($duplicated)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Actualiza los pdf que se encuentran en las carpetas
     *
     * @return $this
     * @throws Exception
     */
    public function updatePdfs()
    {
        // se genera un solo formato para ser guardado en storage, en este caso esta quedando el ultimo formato, en una actualizacion se podria deifinir por configuracion cual formato es el que esta asignado por defecto
        $formats = [
            'ticket',
            'a5',
            'a4',
        ];
        $DownloadController = new DownloadController();
        foreach ($formats as $format) {
            try {
                $DownloadController
                    ->toPrint('document', $this->external_id, $format);
            } catch (ErrorException $e) {
                // do nothing
            }
        }
        return $this;

    }

    /**
     * Devuelve notas de credito o debito que afectan al documento
     *
     * @return Note[]|\Illuminate\Database\Eloquent\Builder[]|EloquentCollection|Builder[]|Collection|mixed
     */
    public function getNotes()
    {
        return Note::where('affected_document_id', $this->id)->get();
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return Document
     */
    public function setTotal(float $total): Document
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Retorna una coleccion de nota de ventas con el formato especificado
     *
     * @return SaleNote[]|\Illuminate\Database\Eloquent\Builder[]|EloquentCollection|Builder[]|Collection|mixed
     */
    public function getNvCollection()
    {

        return SaleNote::where('document_id', $this->id)
            ->orWhere('id', $this->sale_note_id)
            ->get()
            ->transform(function ($sale_note) {
                /** @var SaleNote $sale_note */
                return $sale_note->getCollectionData();
            });
    }

    /**
     * @return array
     */
    public function getOrderNoteCollection()
    {
        $orderNote = OrderNote::find($this->order_note_id);
        if ($orderNote === null) return [];
        return $orderNote->getCollectionData();
    }

    /**
     * Devuelve una coleccion de plataformas web basado en los items.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|EloquentCollection|Builder[]|Collection|mixed|WebPlatform|WebPlatform[]
     */
    public function getPlatformThroughItems()
    {
        /**
         * @var EloquentCollection $items
         * @var WebPlatform $web_platforms
         */
        $items = $this->items->pluck('item_id');
        $web_platform_table_name = (new WebPlatform())->getTable();
        $item_table_name = (new Item())->getTable();
        return WebPlatform::leftJoin('items', "$web_platform_table_name.id", '=', "$item_table_name.web_platform_id")
            ->select("$web_platform_table_name.id", "$web_platform_table_name.name")
            ->wherein("$item_table_name.id", $items)
            ->get();
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereValuedKardexFormatSunat($query, $params)
    {
        return $query->whereStateTypeAccepted()
            ->whereTypeUser()
            ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guide_files()
    {
        return $this->hasMany(GuideFile::class);
    }

    public function tip()
    {
        return $this->morphOne(Tip::class, 'origin');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $establishment_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereEstablishmentId(EloquentBuilder $query, $establishment_id = 0)
    {

        if ($establishment_id != 0) {
            $query->where('establishment_id', $establishment_id);
        }
        return $query;
    }

    /**
     * Devuelve el vendedor asociado, Si seller id es nulo, devolverá el usuario del campo user.
     *
     * @return User
     */
    public function getSellerData()
    {
        if (!empty($this->seller_id)) {
            return $this->seller;
        }
        return $this->user;

    }


    /**
     *
     * Filtros para reportes de comisiones
     * Usado en:
     * Modules\Report\Http\Controllers\ReportCommissionController
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $date_start
     * @param $date_end
     * @param $establishment_id
     * @param $user_type
     * @param $user_seller_id
     * @param $row_user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFilterCommission($query, $date_start, $date_end, $establishment_id, $user_type, $user_seller_id, $row_user_id)
    {

        $query->whereStateTypeAccepted()
            ->whereBetween('date_of_issue', [$date_start, $date_end])
            ->whereEstablishmentId($establishment_id);

        if ($user_seller_id) {
            $query->where($user_type, $user_seller_id);
        } else {
            $query->where($user_type, $row_user_id);
        }

        return $query;
    }


    /**
     * Obtener total del documento verificando el tipo de documento
     * NC descuenta
     * Usado en:
     * Modules\Report\Helpers\UserCommissionHelper para reporte de comisiones
     *
     * @return float
     */
    public function getTotalByDocumentType()
    {
        return $this->document_type_id === '07' ? $this->total * -1 : $this->total;
    }

    /**
     * @return string|null
     */
    public function getGrade(): ?string
    {
        return $this->grade;
    }

    /**
     * @param string|null $grade
     *
     * @return Document
     */
    public function setGrade(?string $grade): Document
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSection(): ?string
    {
        return $this->section;
    }

    /**
     * @param string|null $section
     *
     * @return Document
     */
    public function setSection(?string $section): Document
    {
        $this->section = $section;
        return $this;
    }

    /**
     * Devuelve el modelo del tipo de documetno actual
     *
     * @return DocumentType
     */
    public function getDocumentType()
    {
        return $this->document_type;
    }

    /**
     * @return bool
     */
    public function isHasCdr()
    {
        return (bool)$this->has_cdr;
    }

    /**
     * Retornar placas registradas
     *
     * @return array
     */
    public function getPlateNumbers()
    {
        $plate_numbers = collect();

        if (in_array($this->document_type_id, ['01', '03'])) {

            if ($this->plate_number) return $plate_numbers->push(['description' => $this->plate_number]);

            //obtener las placas registradas por cada item
            $this->items->each(function ($item) use ($plate_numbers) {

                $item->getPlateNumberByItems()->each(function ($row) use ($plate_numbers) {
                    $plate_numbers->push(['description' => $row->value]);
                });

            });

        }

        return $plate_numbers;

    }


    /**
     * Obtener tipo de documento válido para enviar el xml a firmar al pse
     *
     * Usado en:
     * App\CoreFacturalo\Services\Helpers\SendDocumentPse
     *
     * @return string
     */
    public function getDocumentTypeForPse()
    {

        $allowed_document_types = [
            '01' => 'FACT',
            '03' => 'BOLE',
            '07' => 'NOCR',
            '08' => 'NODB',
        ];


        return $allowed_document_types[$this->document_type_id];

    }

    public function getResponseSendCdrPseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setResponseSendCdrPseAttribute($value)
    {
        $this->attributes['response_send_cdr_pse'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getResponseSignaturePseAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setResponseSignaturePseAttribute($value)
    {
        $this->attributes['response_signature_pse'] = (is_null($value)) ? null : json_encode($value);
    }

    /**
     * registros asociados cuando se genera cpe desde multiples notas de venta
     *
     * @param $value
     */
    public function getSaleNotesRelatedsAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    /**
     * registros asociados cuando se genera cpe desde multiples notas de venta
     *
     * @param $value
     */
    public function setSaleNotesRelatedsAttribute($value)
    {
        $this->attributes['sale_notes_relateds'] = (is_null($value)) ? null : json_encode($value);
    }

    /**
     *
     * Filtro para no incluir relaciones en consulta
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFilterWithOutRelations($query)
    {
        return $query->withOut([
            'user',
            'soap_type',
            'state_type',
            'document_type',
            'currency_type',
            'group',
            'items',
            'invoice',
            'note',
            'payments',
            'fee'
        ]);
    }


    /**
     * Obtener diferencia de días en base a la fecha de emisión
     *
     * Usado en:
     * VoidedController - Validación de plazo de envío
     *
     * @param Carbon $value
     * @return int
     */
    public function getDiffInDaysDateOfIssue($value = null)
    {
        $date = $value ?? Carbon::now();

        return $this->date_of_issue->diffInDays($date);
    }


    /**
     * Validar si el documento fue generado a partir de un registro externo
     *
     * Usado en:
     * InventoryKardexServiceProvider
     *
     * @return bool
     */
    public function isGeneratedFromExternalRecord()
    {
        $generated = false;

        if (!is_null($this->order_note_id)) {
            $generated = true;
        }

        // @todo agregar mas registros relacionados

        return $generated;
    }


    /**
     *
     * Filtrar por rango de fechas
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     *
     */
    public function scopeFilterRangeDateOfIssue($query, $date_start, $date_end)
    {
        return $query->whereBetween('date_of_issue', [$date_start, $date_end]);
    }

    /**
     *
     * Filtrar facturas y boletas
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     *
     */
    public function scopeFilterDocumentTypeInvoice($query)
    {
        return $query->whereIn('document_type_id', ['01', '03']);
    }

    /**
     *
     * @return string
     *
     */
    public function getVoidedDescription()
    {
        return $this->state_type_id === '11' ? 'SI' : 'NO';
    }


    /**
     *
     * Obtener pagos en efectivo
     *
     * @return Collection
     */
    public function getCashPayments()
    {
        return $this->payments()->whereFilterCashPayment()->get()->transform(function ($row) {
            {
                return $row->getRowResourceCashPayment();
            }
        });
    }


    /**
     *
     * Validar si el registro esta rechazado o anulado
     *
     * @return bool
     */
    public function isVoidedOrRejected()
    {
        return in_array($this->state_type_id, self::VOIDED_REJECTED_IDS);
    }


    /**
     *
     * Obtener el total de notas de credito de cada cpe
     *
     * @return float
     */
    public function getCreditNotesTotal()
    {
        return $this->affected_documents()
            ->join('documents', 'documents.id', '=', 'notes.document_id')
            ->whereHas('document', function ($query) {
                return $query->whereStateTypeAccepted()->where('document_type_id', '07');
            })
            ->sum('documents.total');
    }


    /**
     *
     * Obtener query de nc para subconsulta de cuentas por cobrar
     *
     * Usado en:
     * DashboardView
     * AccountsReceivable
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getQueryCreditNotes()
    {
        return DB::connection('tenant')
            ->table('notes')
            ->join('documents', 'documents.id', '=', 'notes.document_id')
            ->whereIn('documents.state_type_id', ['01', '03', '05', '07', '13'])
            ->where('documents.document_type_id', '07')
            ->select('affected_document_id', DB::raw('SUM(documents.total) as total_credit_notes'))
            ->groupBy('affected_document_id');
    }


    /**
     *
     * Retornar el total de pagos
     *
     * @return float
     */
    public function getTotalAllPayments()
    {

        $total_payments = 0;

        if (!$this->isVoidedOrRejected()) {
            $total_payments = $this->payments->sum('payment');

            if ($this->currency_type_id === 'USD') {
                $total_payments = $this->generalConvertValueToPen($total_payments, $this->exchange_rate_sale);
            }
        }

        return $total_payments;
    }


    /**
     *
     * Filtrar documentos para generar resumen diario
     *
     * @param Builder $query
     * @param string $date_of_reference
     * @param string $soap_type_id
     * @return Builder
     *
     */
    public function scopeFilterDocumentsForSummary($query, $date_of_reference, $soap_type_id)
    {
        return $query->where('date_of_issue', $date_of_reference)
            ->where('soap_type_id', $soap_type_id)
            ->where('group_id', '02')
            ->where('state_type_id', '01')
            ->where('ticket_single_shipment', false)
            ->take(500);
    }


    /**
     *
     * Validar si la boleta se envio de forma individual
     *
     * @return bool
     */
    public function isSingleDocumentShipment()
    {
        return $this->document_type_id === self::DOCUMENT_TYPE_TICKET && $this->ticket_single_shipment;
    }


    /**
     *
     * Validar si se modifico la boleta enviada de forma individual, a resumen
     *
     * @return bool
     */
    public function isForceSendBySummary()
    {
        return $this->isDocumentTypeTicket() && $this->force_send_by_summary;
    }


    /**
     *
     * Validar si se puede modificar el tipo de envio de la boleta, individual a resumen
     *
     * @return bool
     */
    public function isAvailableForceSendBySummary()
    {
        return $this->isSingleDocumentShipment() && !$this->force_send_by_summary && $this->state_type_id === self::STATE_TYPE_REGISTERED && auth()->user()->permission_force_send_by_summary;
    }


    /**
     *
     * Verificar si es boleta
     *
     * @return bool
     */
    public function isDocumentTypeTicket()
    {
        return $this->document_type_id === self::DOCUMENT_TYPE_TICKET;
    }


    /**
     *
     * Determina si se muestra el boton consultar cdr
     *
     * @return bool
     */
    public function isAvailableConsultCdr()
    {
        $action = false;

        if ($this->state_type_id === self::STATE_TYPE_REGISTERED && $this->soap_type_id === self::SOAP_TYPE_PRODUCTION)
        {
            if($this->group_id === self::GROUP_INVOICE)
            {
                $action = true;
            }
            else
            {
                if($this->isSingleDocumentShipment()) $action = true;
            }
        }

        return $action;
    }


    /**
     *
     * Determina si se muestra el boton para reenvio
     *
     * @return bool
     */
    public function isAvailableResend()
    {
        $action = false;

        if ($this->state_type_id === self::STATE_TYPE_REGISTERED)
        {
            if($this->group_id === self::GROUP_INVOICE)
            {
                $action = true;
            }
            else
            {
                if($this->isSingleDocumentShipment()) $action = true;
            }
        }

        return $action;
    }


    /**
     *
     * Filtrar registros para listado de documentos - app
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public static function scopeFilterRecordsAppApi($query, $request)
    {

        $state_type_id = $request->state_type_id ?? 'all';

        $query->whereTypeUser()
            ->where(function ($q) use ($request) {
                $q->where('series', 'like', "%{$request->input}%")
                    ->orWhere('number', 'like', "%{$request->input}%");
            })
            ->where('document_type_id', $request->document_type_id);


        if ($state_type_id !== 'all') {
            $query->where('state_type_id', $request->state_type_id);
        }

        return $query;
    }

    /**
     *
     * Obtener relaciones necesarias o aplicar filtros para reporte pagos - finanzas
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterRelationsGlobalPayment($query)
    {
        return $query->whereFilterWithOutRelations()
            ->with([
                'document_type' => function ($q) {
                    $q->select('id', 'description');
                },
            ])
            ->select([
                'id',
                'user_id',
                'external_id',
                'establishment_id',
                'soap_type_id',
                'state_type_id',
                'document_type_id',
                'series',
                'number',
                'date_of_issue',
                'time_of_issue',
                'customer_id',
                'customer',
                'currency_type_id',
                'quotation_id',
                'exchange_rate_sale',
                'total',
                'filename',
                'sale_note_id',
                'total_canceled',
                'order_note_id',
                'payment_method_type_id',
                'seller_id',
                'payment_condition_id',
                'dispatch_id',
                'technical_service_id',
            ]);
    }


    /**
     *
     * Determina si es factura o boleta
     *
     * @return bool
     */
    public function isDocumentTypeInvoice()
    {
        return in_array($this->document_type_id, ['01', '03'], true);
    }


    /**
     *
     * Determina si fue usado para sistema por puntos
     *
     * @return bool
     */
    public function isPointSystem()
    {
        return $this->point_system;
    }


    /**
     *
     * Obtener puntos por la venta
     *
     * @return float
     *
     */
    public function getPointsBySale()
    {
        $calculate_quantity_points = 0;

        if($this->isPointSystem())
        {
            $point_system_data = $this->point_system_data;
            $total = $this->total;

            $value_quantity_points = ($total / $point_system_data->point_system_sale_amount) * $point_system_data->quantity_of_points;
            $calculate_quantity_points = $point_system_data->round_points_of_sale ? intval($value_quantity_points) : round($value_quantity_points, 2);
        }

        return $calculate_quantity_points;
    }

    public function getQrAttribute($value)
    {
        if(!is_null($value)) {
            return $value;
        }
        $company = Company::query()->first();
        $customer = $this->customer;
        $text = join('|', [
            $company->number,
            $this->document_type_id,
            $this->series,
            $this->number,
            $this->total_igv,
            $this->total,
            $this->date_of_issue->format('Y-m-d'),
            $customer->identity_document_type_id,
            $customer->number,
            $this->hash
        ]);

        $qrCode = new QrCodeGenerate();
        return $qrCode->displayPNGBase64($text);
    }


    /**
     *
     * @param  string $format
     * @return string
     */
    public function getUrlPrintByFormat($format)
    {
        return url("print/document/{$this->external_id}/{$format}");
    }


    /**
     *
     * Filtrar registro para envio de mensajes por whatsapp
     *
     * @param Builder $query
     * @return Builder
     */
    public static function scopeFilterDataForSendMessage($query)
    {
        return $query->whereFilterWithOutRelations()
                    ->select([
                        'id',
                        'external_id',
                        'series',
                        'number',
                        'filename'
                    ]);
    }


    /**
     *
     * Placa para reporte de ventas
     *
     * @return string
     */
    public function getPlateNumberSaleReport()
    {
        return $this->plate_number;
    }


    /**
     *
     * @return bool
     */
    public function isCreditNote()
    {
        return $this->document_type_id === DocumentType::CREDIT_NOTE_ID;
    }


    /**
     *
     * Determina si es nota credito tipo 13
     *
     * @return bool
     */
    public function isCreditNoteAndType13()
    {
        if($this->isCreditNote())
        {
            if($this->note)
            {
                return $this->note->isTypePaymentDateAdjustments();
            }
        }

        return false;
    }


    /**
     *
     * Tipo de transaccion para caja
     *
     * @return string
     */
    public function getTransactionTypeCash()
    {
        return 'income';
    }


    /**
     *
     * Tipo de documento para caja
     *
     * @return string
     */
    public function getDocumentTypeCash()
    {
        return $this->getTable();
    }


    /**
     *
     * Datos para resumen diario de operaciones
     *
     * @return array
     */
    public function applySummaryDailyOperations()
    {
        return [
            'transaction_type' => $this->getTransactionTypeCash(),
            'document_type' => $this->getDocumentTypeCash(),
            'apply' => $this->hasAcceptedState(),
        ];
    }


    /**
     *
     * Obtener total de pagos en efectivo sin considerar destino
     *
     * @return float
     */
    public function totalCashPaymentsWithoutDestination()
    {
        return $this->payments()->filterCashPaymentWithoutDestination()->sum('payment');
    }


    /**
     *
     * Obtener total de pagos en transferencia
     *
     * @return float
     */
    public function totalTransferPayments()
    {
        return $this->payments()->filterTransferPayment()->sum('payment');
    }


    /**
     *
     * Validar si tiene estado permitido para calculos/etc
     *
     * @return bool
     */
    public function hasAcceptedState()
    {
        return in_array($this->state_type_id, self::STATE_TYPES_ACCEPTED, true);
    }


    /**
     *
     * Aplicar querys para obtener el documento al generar nc/nd
     *
     * Usado en:
     * NoteController
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterDataDocumentAffected($query)
    {
        return $query->with([
            'invoice',
            'items' => function($items){
                return $items->with([
                    'affectation_igv_type'
                ]);
            }
        ]);
    }


    /**
     *
     * @return bool
     */
    public function hasNationalCurrency()
    {
        return $this->currency_type_id === self::NATIONAL_CURRENCY_ID;
    }


    /**
     *
     * Obtener base imponible de la retencion en soles
     *
     * @return float
     */
    public function getRetentionTaxBase()
    {
        $base = 0;

        if($this->retention)
        {
            $base = $this->hasNationalCurrency() ? $this->retention->base : $this->generalConvertValueToPen($this->retention->base, $this->retention->exchange_rate);
        }

        return round($base, 2);
    }


    /**
     *
     * @param  float $value
     * @return float
     */
    public function getValueGreaterZero($value)
    {
        if($value > 0) return $this->generalApplyNumberFormat($value);

        return null;
    }

    /**
     *
     * Cargar relaciones para edicion
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeApplyWithsShow($query)
    {
        return $query->with([
            'items' => function($items){
                return $items->with(['affectation_igv_type', 'system_isc_type']);
            },
            'payments',
            'fee',
            'invoice'
        ]);
    }

    /**
     * relacion con tabla cat_global_factoring_api_states para servicio de api
     */
    public function api_state()
    {
        return $this->belongsTo(ApiState::class, 'collect_api_state_id');
    }

}