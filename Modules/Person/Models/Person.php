<?php

namespace Modules\Person\Models;

use Modules\Catalog\Models\AddressType;
use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\IdentityDocumentType;
use Modules\Catalog\Models\Province;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;
use Modules\Dispatch\Models\Dispatch;
use Modules\Document\Models\Document;
use Modules\DocumentaryProcedure\Models\DocumentaryFile;
use Modules\Expense\Models\Expense;
use Modules\FullSubscription\Models\FullSubscriptionServerDatum;
use Modules\FullSubscription\Models\FullSubscriptionUserDatum;
use Modules\Item\Models\Zone;
use Modules\OrderForm\Models\OrderForm;
use Modules\OrderNote\Models\OrderNote;
use Modules\Perception\Models\Perception;
use Modules\Purchase\Models\FixedAssetPurchase;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseSettlement;
use Modules\Quotation\Models\Quotation;
use Modules\Retention\Models\Retention;
use Modules\Sale\Models\Contract;
use Modules\Sale\Models\SaleOpportunity;
use Modules\SaleNote\Models\SaleNote;
use Modules\TechnicalService\Models\TechnicalService;
use Modules\User\Models\User;

class Person extends ModelTenant
{
    protected $table = 'persons';

    protected $fillable = [
        'type',
        'identity_document_type_id',
        'number',
        'name',
        'text_filter',
        'trade_name',
        'internal_code',
        'country_id',
        'nationality_id',
        'department_id',
        'province_id',
        'district_id',
        'address_type_id',
        'address',
        'condition',
        'state',
        'email',
        'telephone',
        'perception_agent',
        'person_type_id',
        'contact',
        'comment',
        'percentage_perception',
        'enabled',
        'website',
        'barcode',
        // 'zone',
        'observation',
        'credit_days',
        'optional_email',
        'seller_id',
        'zone_id',
        'status',
        'parent_id',
        'accumulated_points',
        'has_discount',
        'discount_type',
        'discount_amount',
        'is_agent_retention',
    ];

    protected $casts = [
        'perception_agent' => 'bool',
        'person_type_id' => 'int',
        'percentage_perception' => 'float',
        'enabled' => 'bool',
        'status' => 'int',
        'credit_days' => 'int',
        'seller_id' => 'int',
        'zone_id' => 'int',
        'parent_id' => 'int',
        'accumulated_points' => 'float',
        'has_discount' => 'bool',
        'discount_amount' => 'float',
        'is_agent_retention' => 'bool'
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('active', function (Builder $builder) {
    //         $builder->where('status', 1);
    //     });
    // }

    /**
     * Devuelve un conjunto de hijos basado en parent_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children_person()
    {
        return $this->hasMany(Person::class, 'parent_id');
    }

    /**
     * Devuelve el padre basado en parent_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent_person()
    {
        return $this->belongsTo(Person::class, 'parent_id');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function person_addresses()
    {
        return $this->hasMany(PersonAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(PersonAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents_where_customer()
    {
        return $this->hasMany(Document::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address_type()
    {
        return $this->belongsTo(AddressType::class);
    }

    /**
     * @param $query
     * @param $type
     *
     * @return mixed
     */
    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getAddressFullAttribute()
    {
        $address = trim($this->address);
        $address = ($address === '-' || $address === '') ? '' : $address . ' ,';
        if ($address === '') {
            return '';
        }
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function more_address()
    {
        return $this->hasMany(PersonAddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person_type()
    {
        return $this->belongsTo(PersonType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts_where_customer()
    {
        return $this->hasMany(Contract::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispatches_where_customer()
    {
        return $this->hasMany(Dispatch::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentary_files()
    {
        return $this->hasMany(DocumentaryFile::class);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereIsEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses_where_supplier()
    {
        return $this->hasMany(Expense::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fixed_asset_purchases_where_customer()
    {
        return $this->hasMany(FixedAssetPurchase::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fixed_asset_purchases_where_supplier()
    {
        return $this->hasMany(FixedAssetPurchase::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_forms_where_customer()
    {
        return $this->hasMany(OrderForm::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_notes_where_customer()
    {
        return $this->hasMany(OrderNote::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function perceptions_where_customer()
    {
        return $this->hasMany(Perception::class, 'customer_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchase_orders_where_supplier()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchase_settlements_where_supplier()
    {
        return $this->hasMany(PurchaseSettlement::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases_where_customer()
    {
        return $this->hasMany(Purchase::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases_where_supplier()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotations_where_customer()
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retentions_where_supplier()
    {
        return $this->hasMany(Retention::class, 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sale_notes_where_customer()
    {
        return $this->hasMany(SaleNote::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sale_opportunities_where_customer()
    {
        return $this->hasMany(SaleOpportunity::class, 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function technical_services_where_customer()
    {
        return $this->hasMany(TechnicalService::class, 'customer_id');
    }

    public function getContactAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setContactAttribute($value)
    {
        $this->attributes['contact'] = (is_null($value)) ? null : json_encode($value);
    }

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param bool $withFullAddress
     * @param bool $childrens
     *
     * @return array
     */
    public function getCollectionData($withFullAddress = false, $childrens = false, $servers = false)
    {
        $addresses = $this->addresses;
        if ($withFullAddress == true) {
            $addresses = collect($addresses)->transform(function ($row) {
                return $row->getCollectionData();
            });
        }
        $person_type_descripton = '';
        if ($this->person_type !== null) {
            $person_type_descripton = $this->person_type->description;
        }
        $optional_mail = $this->getOptionalEmailArray();
        $optional_mail_send = [];
        if (!empty($this->email)) {
            $optional_mail_send[] = $this->email;
        }
        $total_optional_mail = count($optional_mail);
        for ($i = 0; $i < $total_optional_mail; $i++) {
            $temp = trim($optional_mail[$i]['email']);
            if (!empty($temp) && $temp != $this->email) {
                $optional_mail_send[] = $temp;
            }
        }
        $department = \Modules\Catalog\Models\Department::find($this->department_id);
        if (!empty($department)) {
            $department = [
                "id" => $department->id,
                "description" => $department->description,
                "active" => $department->active,
            ];
        }

        $location_id = [];
        $department = \Modules\Catalog\Models\Department::find($this->department_id);
        if (!empty($department)) {
            $department = [
                "id" => $department->id,
                "description" => $department->description,
                "active" => $department->active,
            ];
            array_push($location_id, $department['id']);
        }
        $province = Province::find($this->province_id);

        if (!empty($province)) {
            $province = [
                "id" => $province->id,
                "description" => $province->description,
                "active" => $province->active,
            ];
            array_push($location_id, $province['id']);
        }
        $district = District::find($this->district_id);

        if (!empty($district)) {
            $district = [
                "id" => $district->id,
                "description" => $district->description,
                "active" => $district->active,
            ];
            array_push($location_id, $district['id']);
        }
        $seller = User::find($this->seller_id);
        if (!empty($seller)) {
            $seller = $seller->getCollectionData();
        }

        $data = [
            'id' => $this->id,
            'description' => $this->number . ' - ' . $this->name,
            'name' => $this->name,
            'number' => $this->number,
            'identity_document_type_id' => $this->identity_document_type_id,
            'identity_document_type_code' => $this->identity_document_type->code,
            'address' => $this->address,
            'internal_code' => $this->internal_code,
            'barcode' => $this->barcode,
            'observation' => $this->observation,
            'seller' => $seller,
            'zone' => $this->getZone(),
            'zone_id' => $this->zone_id,
            'seller_id' => $this->seller_id,
            'website' => $this->website,
            'document_type' => $this->identity_document_type->description,
            'enabled' => (bool)$this->enabled,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'type' => $this->type,
            'trade_name' => $this->trade_name,
            'country_id' => $this->country_id,
            'nationality_id' => $this->nationality_id,
            'department_id' => $department['id'] ?? null,
            'department' => $department,

            'province_id' => $province['id'] ?? null,
            'province' => $province,
            'district_id' => $district['id'] ?? null,
            'district' => $district,

            'telephone' => $this->telephone,
            'email' => $this->email,
            'perception_agent' => (bool)$this->perception_agent,
            'percentage_perception' => $this->percentage_perception,
            'is_agent_retention' => $this->is_agent_retention,
            'state' => $this->state,
            'condition' => $this->condition,
            'person_type_id' => $this->person_type_id,
            'person_type' => $person_type_descripton,
            'contact' => $this->contact,
            'comment' => $this->comment,
            'addresses' => $addresses,
            'parent_id' => $this->parent_id,
            'credit_days' => (int)$this->credit_days,
            'optional_email' => $optional_mail,
            'optional_email_send' => implode(',', $optional_mail_send),
            'childrens' => [],
            'accumulated_points' => $this->accumulated_points,
            'has_discount' => $this->has_discount,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
            'location_id' => $location_id,
            'search_full_name' => $this->search_full_name,
        ];
        if ($childrens == true) {
            $child = $this->children_person->transform(function ($row) {
                return $row->getCollectionData();
            });
            $data['childrens'] = $child;
            $parent = null;
            if ($this->parent_person) {
                $parent = $this->parent_person->getCollectionData();
            }

            $data['parent'] = $parent;

        }

        if ($servers == true) {
            $serv = FullSubscriptionServerDatum::where('person_id', $this->id)->get();
            $extra_data = FullSubscriptionUserDatum::where('person_id', $this->id)->first();
            if (empty($extra_data)) {
                $extra_data = new FullSubscriptionUserDatum();
            }
            $data['servers'] = $serv;
            $data['person_id'] = $extra_data->getPersonId();
            $data['discord_user'] = $extra_data->getDiscordUser();
            $data['slack_channel'] = $extra_data->getSlackChannel();
            $data['discord_channel'] = $extra_data->getDiscordChannel();
            $data['gitlab_user'] = $extra_data->getGitlabUser();

        }

        return $data;
    }

    /**
     * @return array
     */
    public function getOptionalEmailArray(): array
    {
        $data = unserialize($this->optional_email);
        if ($data === false) {
            $data = [];
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getObservation(): string
    {
        return $this->observation;
    }

    /**
     * @param string $observation
     *
     * @return Person
     */
    public function setObservation(string $observation): Person
    {
        $this->observation = $observation;
        return $this;
    }


    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     *
     * @return Person
     */
    public function setWebsite(string $website): Person
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOptionalEmail(): ?string
    {
        return $this->optional_email;
    }

    /**
     * @param string|null $optional_email
     *
     * @return Person
     */
    public function setOptionalEmail(?string $optional_email): Person
    {
        $this->optional_email = $optional_email;
        return $this;
    }

    /**
     * @param array $optional_email_array
     *
     * @return Person
     */
    public function setOptionalEmailArray(array $optional_email_array = []): Person
    {
        $this->optional_email = serialize($optional_email_array);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return (int)$this->parent_id;
    }

    /**
     * @param int|null $parent_id
     *
     * @return Person
     */
    public function setParentId(?int $parent_id): Person
    {
        $this->parent_id = (int)$parent_id;
        return $this;
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function getZone()
    {
        return Zone::find($this->zone_id);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function scopeSearchCustomer(Builder $query, $dni_ruc, $name = null, $email = null)
    {
        $query->where('type', 'customers');
        $query->where('number', $dni_ruc);
        if (!empty($name)) {
            $query->where('name', 'like', "%$name%");
        }
        if (!empty($email)) {
            $query->where('email', 'like', "%$email%");
        }

        return $query;

    }


    /**
     *
     * Aplicar filtro por vendedor asignado al cliente
     *
     * Usado en:
     * PersonController - records
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFilterCustomerBySeller($query, $type)
    {
        if ($type === 'customers') {
            $user = auth()->user();

            if ($user->applyCustomerFilterBySeller()) {
                return $query->where('seller_id', $user->id);
            }
        }

        return $query;
    }


    /**
     *
     * Obtener datos para api (app)
     *
     * @return array
     */
    public function getApiRowResource()
    {
        return [
            'id' => $this->id,
            'description' => $this->getPersonDescription(),
            'name' => $this->name,
            'number' => $this->number,
            'identity_document_type_id' => $this->identity_document_type_id,
            'identity_document_type_code' => $this->identity_document_type->code,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'country_id' => $this->country_id,
            'district_id' => $this->district_id,
            'email' => $this->email,
            'enabled' => $this->enabled,
            'selected' => false,
            'identity_document_type_description' => $this->identity_document_type->description,
        ];
    }


    /**
     *
     * Descripción para mostrar en campos de búsqueda, etc
     *
     * @return string
     */
    public function getPersonDescription()
    {
        return "{$this->number} - {$this->name}";
    }


    /**
     *
     * Filtro para búsqueda de clientes/proveedores
     *
     * Usado en:
     * clientes - app
     *
     * @param Builder $query
     * @param string $input
     * @param string $type
     * @return Builder
     */
    public function scopeWhereFilterRecordsApi($query, $input, $type)
    {
        return $query->where('name', 'like', "%{$input}%")
            ->orWhere('number', 'like', "%{$input}%")
            ->whereType($type)
            ->orderBy('name');
    }


    /**
     *
     * @return string
     */
    public function getTitlePersonDescription()
    {
        return $this->type === 'customers' ? 'Cliente' : 'Proveedor';
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
            'identity_document_type',
            'country',
            'department',
            'province',
            'district'
        ]);
    }


    /**
     * Obtener datos iniciales para mostrar lista de clientes - App
     *
     * @param int $take
     * @return array
     */
    public function scopeFilterApiInitialCustomers($query, $take = 10)
    {
        return $query->whereType('customers')
            ->whereFilterWithOutRelations()
            ->with(['identity_document_type'])
            ->orderBy('name')
            ->take($take);
    }

    /**
     *
     * Filtro para cliente varios por defecto
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhereFilterVariousClients($query)
    {
        return $query->where([
            ['identity_document_type_id', '0'],
            ['number', '99999999'],
            ['type', 'customers'],
        ]);
    }


    /**
     *
     * Obtener puntos acumulados
     *
     * @param Builder $query
     * @param int $id
     * @return float
     */
    public function scopeGetOnlyAccumulatedPoints($query, $id)
    {
        return $query->whereFilterWithOutRelations()->select('accumulated_points')->findOrFail($id)->accumulated_points;
    }

        
    /**
     *
     * @return string
     */
    public function getSearchFullNameAttribute()
    {
        return "{$this->number} - {$this->name}";
    }

    
    /**
     *
     * @return array
     */
    public function getSearchDataResource()
    { 
        return [
            'id' => $this->id,
            'search_full_name' => $this->search_full_name,
            'identity_document_type_id' => $this->identity_document_type_id, 
        ];
    }
    

    /**
     * 
     * Filtro para busqueda
     *
     * @param  Builder $query
     * @param  Request $request
     * @return Builder
     */
    public function scopeWhereFilterSearchData($query, $request)
    {
        return $query->generalWhereLikeColumn('number', $request->input)
                    ->generalOrWhereLikeColumn('name', $request->input);
    }
    
    
    /**
     * 
     * Filtros opcionales para componente de busqueda
     *
     * @param  Builder $query
     * @param  string $type
     * @return Builder
     */
    public function scopeOptionalFiltersSearchData($query, $type)
    {
        return $query->whereType($type);
    }

    
    /**
     * 
     * Columnas para listado de tablas relacionadas
     *
     * @param  Builder $query
     * @param  array $columns
     * @return Builder
     */
    public function scopeSelectColumnsRelationRecords($query, $columns = [])
    {
        if(empty($columns)) $columns = ['id', 'name', 'number'];

        return $query->select($columns);
    }


}
