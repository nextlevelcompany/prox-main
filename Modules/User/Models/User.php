<?php

namespace Modules\User\Models;

use App\Models\Tenant\ModelTenant;
use App\Notifications\Tenant\PasswordResetNotification;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Cash\Models\Cash;
use Modules\Dispatch\Models\Dispatch;
use Modules\Document\Models\Document;
use Modules\DocumentaryProcedure\Models\DocumentaryFile;
use Modules\Establishment\Models\Establishment;
use Modules\Establishment\Models\Series;
use Modules\Expense\Models\Expense;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\Income;
use Modules\Inventory\Models\Devolution;
use Modules\Item\Models\ItemsRating;
use Modules\Item\Models\Zone;
use Modules\LevelAccess\Models\Module;
use Modules\LevelAccess\Models\ModuleLevel;
use Modules\OrderForm\Models\OrderForm;
use Modules\OrderNote\Models\OrderNote;
use Modules\Perception\Models\Perception;
use Modules\Purchase\Models\FixedAssetPurchase;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Purchase\Models\PurchaseQuotation;
use Modules\Purchase\Models\PurchaseSettlement;
use Modules\Quotation\Models\Quotation;
use Modules\Retention\Models\Retention;
use Modules\Sale\Models\Contract;
use Modules\Sale\Models\SaleOpportunity;
use Modules\SaleNote\Models\SaleNote;
use Modules\Summary\Models\Summary;
use Modules\TechnicalService\Models\TechnicalService;
use Modules\Sale\Models\UserCommission;
use Modules\Company\Models\Configuration;
use Modules\Restaurant\Models\RestaurantRole;
use Modules\MobileApp\Models\AppModule;
use Modules\LevelAccess\Models\SystemActivityLog;
use Modules\LevelAccess\Models\AuthorizedDiscountUser;
use Modules\Voided\Models\Voided;

class User extends Authenticatable
{
    use Notifiable;
    use UsesTenantConnection;

    public const MAIN_USER_ID = 1;

    protected $fillable = [
        'name',
        'email',
        'password',
        'establishment_id',
        'type',
        'locked',
        'identity_document_type_id',
        'number',
        'address',
        'telephone',
        'document_id',
        'series_id',
        'permission_edit_cpe',
        'recreate_documents',
        'zone_id',
        'restaurant_role_id',

        'delete_payment',
        'create_payment',

        'edit_purchase',
        'annular_purchase',
        'delete_purchase',

        'last_password_update',

        // 'email_verified_at',
        // 'api_token',
        // 'remember_token',

        // informacion personal
        'names',
        'last_names',
        'personal_email',
        'corporate_email',
        'personal_cell_phone',
        'corporate_cell_phone',
        'date_of_birth',
        'contract_date',
        'position',
        'photo_filename',
        // informacion personal

        'multiple_default_document_types',
        'permission_force_send_by_summary',
        'change_seller',
        'permission_edit_sale_note',
        'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',

    ];

    protected $casts = [
        'series_id' => 'int',
        'permission_edit_cpe' => 'boolean',
        'recreate_documents' => 'boolean',
        'establishment_id' => 'int',
        'zone_id' => 'int',
        'locked' => 'bool',

        'delete_payment' => 'bool',
        'create_payment' => 'bool',

        'edit_purchase' => 'bool',
        'annular_purchase' => 'bool',
        'delete_purchase' => 'bool',
        'multiple_default_document_types' => 'bool',
        'permission_force_send_by_summary' => 'boolean',
        'change_seller' => 'boolean',
        'active' => 'boolean',
    ];

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class);
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(ModuleLevel::class);
    }

    public function default_document_types(): HasMany
    {
        return $this->hasMany(UserDefaultDocumentType::class);
    }

    public function authorizeModules($modules)
    {
        if ($this->hasAnyModule($modules)) {
            return true;
        }
        abort(401,
            'Esta acción no está autorizada.');
    }

    public function hasAnyModule($modules)
    {
        if (is_array($modules)) {
            foreach ($modules as $module) {
                if ($this->hasModule($module)) {
                    return true;
                }
            }
        } else {
            if ($this->hasModule($modules)) {
                return true;
            }
        }
        return false;
    }

    public function hasModule($module)
    {
        if ($this->modules()->where('name',
            $module)->first()) {
            return true;
        }
        return false;
    }


    public function getModule()
    {
        $module = $this->modules()->orderBy('id')->first();
        if ($module) {
            return $module->value;
        }
        return null;
    }

    public function getModules()
    {
        $modules = $this->modules()->get();
        if ($modules) {
            return $modules;
        }
        return null;
    }


    public function searchModule($module)
    {
        if ($this->modules()->where('value',
            $module)->first()) {
            return true;
        }
        return false;
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function seller_documents(): HasMany
    {
        return $this->hasMany(Document::class,
            'seller_id',
            'id');
    }

    public function sale_notes(): HasMany
    {
        return $this->hasMany(SaleNote::class);
    }

    public function seller_sale_notes(): HasMany
    {
        return $this->hasMany(SaleNote::class,
            'seller_id',
            'id');
    }

    public function restaurant_role(): BelongsTo
    {
        return $this->belongsTo(RestaurantRole::class);
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('id',
            $user->id) : null;
    }


    public function getLevel()
    {
        $level = $this->levels()->orderBy('id')->first();
        if ($level) {
            return $level->value;
        }
        return null;
    }

    public function getLevels()
    {
        $levels = $this->levels()->get();
        if ($levels) {
            return $levels;
        }
        return null;
    }


    public function searchLevel($Level)
    {
        if ($this->levels()->where('value',
            $Level)->first()) {
            return true;
        }
        return false;
    }

    /**
     * @return HasOne
     */
    public function user_commission(): HasOne
    {
        return $this->hasOne(UserCommission::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * @return mixed
     */
    public function getDocumentId()
    {
        return $this->document_id;
    }

    /**
     * @param mixed $document_id
     *
     * @return User
     */
    public function setDocumentId($document_id)
    {
        $this->document_id = $document_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeriesId()
    {
        return $this->series_id;
    }

    /**
     * @param mixed $series_id
     *
     * @return User
     */
    public function setSeriesId($series_id)
    {
        $this->series_id = $series_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Establece los niveles y modulos del usuario
     * @param array $modules
     * @param array $modules_levels
     *
     * @return $this
     */
    public function setModuleAndLevelModule($modules = [],
                                            $modules_levels = [])
    {
        $user_array = [
            'user_id' => $this->id,

        ];
        /*** Estableciendo los modulos */
        /** @var array $module_array */
        $module_array = $modules;

        $work = DB::connection('tenant')
            ->table('module_user')
            ->where($user_array);

        $deletes = $work
            ->whereNotIn('module_id',
                $module_array)
            ->delete();
        $total_modules = count($module_array);
        for ($i = 0; $i < $total_modules; $i++) {
            $item = (int)$module_array[$i];
            $module_ = $work
                ->where([
                    'module_id' => $item,

                ])->first();
            if (empty($module_)) {
                $user_array['module_id'] = $item;
                $work->insert($user_array);
            }
        }
        unset($user_array['module_id']);

        $levels_array = $modules_levels;

        $work = DB::connection('tenant')
            ->table('module_level_user')
            ->where($user_array);
        $deletes = $work->whereNotIn('module_level_id',
            $levels_array)
            ->delete();

        $total_modules_levels = count($levels_array);

        for ($i = 0; $i < $total_modules_levels; $i++) {
            $item = (int)$levels_array[$i];


            $module_ = $work
                ->where([
                    'module_level_id' => $item,

                ])->first();
            if (empty($module_)) {
                $user_array['module_level_id'] = $item;
                $work->insert($user_array);
            }
        }
        return $this;
    }

    /**
     * Obtiene los niveles de modulo definidos por tenant
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentModuleLevelByTenant()
    {
        return DB::connection('tenant')
            ->table('module_level_user')
            ->select('module_level_id')
            ->where('user_id',
                $this->id)
            ->get();

    }

    /**
     * Obtiene los modulo definidos por tenant
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentModuleByTenant()
    {
        return DB::connection('tenant')
            ->table('module_user')
            ->select('module_id')
            ->where('user_id',
                $this->id)
            ->get();

    }

    /**
     * Devuelve una lista de usuarios vendedores junto con el usuario actual.
     * Si $withEstablishment es verdadero,
     * devuelve usuarios con establecimientos asignados carlomagno83/facturadorpro4#627
     * Si $withEstablishment es falso,
     * devuelve usuarios sin establecimientos asignados carlomagno83/facturadorpro4#233
     *
     * @param \Illuminate\Database\Query\Builder|Builder $query
     * @param bool $withEstablishment
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     */
    public function scopeGetSellers($query,
                                    $withEstablishment = true)
    {
        if ($withEstablishment == false) {
            $query->without(['establishment']);
        } else {
            $query->with(['establishment']);

        }
        $query->whereIn('type',
            ['seller']);
        $query->orWhere('id',
            auth()->user()->id);

        return $query->filterActiveUser();
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeGetWorkers($query)
    {
        $query->whereIn('type',
            ['seller',
                'admin']);
        return $query;
    }

    /**
     * Devuelve verdadero si el usuario es Admin.
     * @return bool
     */
    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    /**
     * Genera un token al azar de $length caracteres
     * @param int|null $length
     * @return $this
     */
    public function updateToken($length = 60)
    {
        $this->api_token = Str::random($length);
        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionData()
    {
        $type = '';
        switch ($this->type) {
            case 'admin':
                $type = 'Administrador';
                break;
            case 'seller':
                $type = 'Vendedor';
                break;
            case 'client':
                $type = 'Cliente';
                break;
            default:
                # code...
                break;
        }

        return [
            'id' => $this->id,

            'email' => $this->email,

            'name' => $this->name,

            'api_token' => $this->api_token,

            'document_id' => $this->document_id,

            'serie_id' => ($this->series_id == 0) ? null : $this->series_id,

            'establishment_description' => optional($this->establishment)->description,

            'type' => $type,

            'locked' => (bool)$this->locked,

        ];
    }

    /**
     * @return array
     */
    public function getCollectionRestaurantData()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'restaurant_role_id' => $this->restaurant_role_id,
            'restaurant_role_name' => $this->restaurant_role_id ? $this->restaurant_role->name : '',
            'restaurant_role_code' => $this->restaurant_role_id ? $this->restaurant_role->code : '',
            'locked' => (bool)$this->locked,
        ];
    }

    /**
     * @return HasMany
     */
    public function cashes()
    {
        return $this->hasMany(Cash::class);
    }

    /**
     * @return HasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * @return HasMany
     */
    public function devolutions()
    {
        return $this->hasMany(Devolution::class);
    }

    /**
     * @return HasMany
     */
    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }

    /**
     * @return HasMany
     */
    public function documentary_files()
    {
        return $this->hasMany(DocumentaryFile::class);
    }

    /**
     * @return HasMany
     */
    public function documents_where_seller()
    {
        return $this->hasMany(Document::class, 'seller_id');
    }

    /**
     * @return HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * @return HasMany
     */
    public function fixed_asset_purchases()
    {
        return $this->hasMany(FixedAssetPurchase::class);
    }

    /**
     * @return HasMany
     */
    public function global_payments()
    {
        return $this->hasMany(GlobalPayment::class);
    }

    /**
     * @return HasMany
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * @return HasMany
     */
    public function items_ratings()
    {
        return $this->hasMany(ItemsRating::class);
    }

    /**
     * @return HasMany
     */
    public function order_forms()
    {
        return $this->hasMany(OrderForm::class);
    }

    /**
     * @return HasMany
     */
    public function order_notes()
    {
        return $this->hasMany(OrderNote::class);
    }

    /**
     * @return HasMany
     */
    public function perceptions()
    {
        return $this->hasMany(Perception::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_quotations()
    {
        return $this->hasMany(PurchaseQuotation::class);
    }

    /**
     * @return HasMany
     */
    public function purchase_settlements()
    {
        return $this->hasMany(PurchaseSettlement::class);
    }

    /**
     * @return HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * @return HasMany
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * @return HasMany
     */
    public function retentions()
    {
        return $this->hasMany(Retention::class);
    }

    /**
     * @return HasMany
     */
    public function sale_opportunities()
    {
        return $this->hasMany(SaleOpportunity::class);
    }

    /**
     * @return HasMany
     */
    public function summaries()
    {
        return $this->hasMany(Summary::class);
    }

    /**
     * @return HasMany
     */
    public function technical_services()
    {
        return $this->hasMany(TechnicalService::class);
    }

    /**
     * @return HasMany
     */
    public function user_commissions()
    {
        return $this->hasMany(UserCommission::class);
    }

    /**
     * @return HasMany
     */
    public function voideds()
    {
        return $this->hasMany(Voided::class);
    }

    public function app_modules()
    {
        return $this->belongsToMany(AppModule::class);
    }

    /**
     * @return HasMany
     */
    public function authorized_discount_users()
    {
        return $this->hasMany(AuthorizedDiscountUser::class);
    }

    public function getSeries()
    {

        $document_id = $this->document_id;
        $series_id = $this->series_id;
        $establishment_id = $this->establishment_id;
        $userType = $this->type;

        return Series::FilterSeries($establishment_id)
            ->get()
            ->transform(function ($row) use ($document_id, $series_id, $userType) {
                /** @var Series $row */
                return $row->getCollectionData($document_id, $series_id, $userType);
            })->where('disabled', false);
    }

    /**
     * @return BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    
    /**
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterActiveUser($query)
    {
        return $query->where('active', true);
    }
    

    /**
     * Devuelve una coleccion de usuarios vendedores para CPE y NV
     * @param int $establishment_id
     * @param int $userId
     *
     * @return User[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function getSellersToNvCpe($establishment_id = 0, $userId = 0)
    {
        return self::where('establishment_id', $establishment_id)
            ->whereIn('type', ['seller', 'admin'])->orWhere('id', $userId)
            ->filterActiveUser()
            ->get();

    }


    /**
     *
     * Validar si aplica el filtro por vendedor para el usuario en sesión (filtrar clientes por vendedor asignado)
     *
     * Usado en:
     * Person - scopeWhereFilterCustomerBySeller
     *
     * @return bool
     */
    public function applyCustomerFilterBySeller()
    {
        $configuration = Configuration::select('customer_filter_by_seller')->first();

        return ($this->type === 'seller' && $configuration->customer_filter_by_seller);
    }


    /**
     *
     * Obtener permisos para pagos de comprobantes
     *
     * @return array
     */
    public function getPermissionsPayment()
    {
        return [
            'create_payment' => $this->create_payment,
            'delete_payment' => $this->delete_payment,
        ];
    }


    /**
     *
     * Retorna data para los permisos de la app
     *
     * @return array
     */
    public function getRowAppPermission()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'app_modules' => $this->getDataAppModules()
        ];
    }


    /**
     *
     * Obtener modulos de la app
     *
     * @return array
     */
    public function getDataAppModules()
    {

        $app_modules = [];
        $all_app_modules = AppModule::get();

        $selected_app_modules = $this->app_modules->pluck('id')->toArray();

        foreach ($all_app_modules as $app_module) {
            $app_modules[] = [
                'id' => $app_module->id,
                'description' => $app_module->description,
                'value' => $app_module->value,
                'checked' => (bool)in_array($app_module->id, $selected_app_modules)
            ];
        }

        return $app_modules;
    }


    /**
     *
     * Obtener permisos del usuario para gestionar modulos en la app
     *
     * @return array
     */
    public function getAppPermission()
    {
        // si es usuario principal y no tiene permisos asignados
        if ($this->id === 1 && $this->type === 'admin') // if($this->id === 1 && $this->type === 'admin' && $this->app_modules->count() === 0)
        {
            return $this->getTransformPermissionsApp(AppModule::get());
        }

        return $this->getTransformPermissionsApp($this->app_modules);

    }


    /**
     *
     * Obtener modulos/opciones disponibles en pos app
     *
     * @return array
     */
    public function getPosDocumentTypes()
    {
        return [
            ['document_type_id' => '01', 'module' => 'invoice'],
            ['document_type_id' => '03', 'module' => 'invoice-ticket'],
            ['document_type_id' => '80', 'module' => 'sale-note'],
        ];
    }


    /**
     *
     * Retornar data para api
     *
     * @param array $data
     * @return array
     */
    public function getTransformPermissionsApp($data)
    {
        return $data->transform(function ($row) {
            return $row->getPermissionsApp();
        });
    }


    /**
     *
     * Obtener datos generales del usuario
     *
     * Usado para carga inicial en app
     *
     * @return array
     */
    public function getGeneralDataApp()
    {
        return [
            'type' => $this->type,
            'establishment_id' => $this->establishment_id,
        ];
    }

    public function getPermissionsPurchase()
    {
        return [
            'edit_purchase' => $this->edit_purchase,
            'annular_purchase' => $this->annular_purchase,
            'delete_purchase' => $this->delete_purchase,
        ];
    }


    /**
     *
     * @return string
     */
    public function getPhotoForView()
    {
        return $this->photo_filename ? (new ModelTenant)->getPathPublicUploads('users', $this->photo_filename) : null;
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
            'establishment',
        ]);
    }


    /**
     *
     * Retorna nombre de la conexión
     *
     * @return string
     */
    public function getDbConnectionName()
    {
        return $this->getConnection()->getName();
    }


    public function system_activity_logs()
    {
        return $this->morphMany(SystemActivityLog::class, 'origin');
    }


    /**
     *
     * Filtro para no incluir relaciones en consulta y obtener el nombre de usuario
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterOnlyUsername($query)
    {
        return $query->whereFilterWithOutRelations()->select('id', 'name');
    }


    public function getDataOnlyAuthUser()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'establishment_id' => $this->establishment_id,
            'type' => $this->type,
            'locked' => $this->locked,
            'identity_document_type_id' => $this->identity_document_type_id,
            'number' => $this->number,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'document_id' => $this->document_id,
            'series_id' => $this->series_id,
            'permission_edit_cpe' => $this->permission_edit_cpe,
            'recreate_documents' => $this->recreate_documents,
            'zone_id' => $this->zone_id,
            'restaurant_role_id' => $this->restaurant_role_id,
            'delete_payment' => $this->delete_payment,
            'create_payment' => $this->create_payment,
            'edit_purchase' => $this->edit_purchase,
            'annular_purchase' => $this->annular_purchase,
            'delete_purchase' => $this->delete_purchase,
            'names' => $this->names,
            'last_names' => $this->last_names,
            'personal_email' => $this->personal_email,
            'corporate_email' => $this->corporate_email,
            'personal_cell_phone' => $this->personal_cell_phone,
            'corporate_cell_phone' => $this->corporate_cell_phone,
            'date_of_birth' => $this->date_of_birth,
            'contract_date' => $this->contract_date,
            'position' => $this->position,
            'photo_filename' => $this->photo_filename,
            'multiple_default_document_types' => $this->multiple_default_document_types,
            'default_document_types' => $this->default_document_types,
            'permission_force_send_by_summary' => $this->permission_force_send_by_summary,
        ];
    }


    /**
     *
     * Permisos de los modulos y submodulos por usuario
     *
     * @return array
     */
    public function getWebPermissionsByUser()
    {
        $modules_id = $this->getCurrentModuleByTenant()->pluck('module_id')->toArray();
        $levels_id = $this->getCurrentModuleLevelByTenant()->pluck('module_level_id')->toArray();
        $modules = Module::whereIn('id', $modules_id)->get();
        $show_modules = [];

        foreach ($modules as $module) {
            $show_modules [] = [
                'id' => $module->id,
                'value' => $module->value,
                'description' => $module->description,
                'levels' => $module->levels()->whereIn('id', $levels_id)->select(['id', 'value', 'description', 'module_id'])->get(),
            ];
        }

        return $show_modules;
    }

    
    /**
     * Verificar si es usuario principal
     *
     * @return bool
     */
    public function getIsMainUserAttribute()
    {
        return $this->id === self::MAIN_USER_ID;
    }

}
