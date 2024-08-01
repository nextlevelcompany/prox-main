<?php

namespace Modules\Expense\Models;

use Modules\Catalog\Models\CurrencyType;
use Modules\Establishment\Models\Establishment;
use App\Models\Tenant\ModelTenant;
use Modules\Person\Models\Person;
use Modules\Company\Models\SoapType;
use Modules\Company\Models\StateType;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends ModelTenant
{
    protected $fillable = [
        'user_id',
        'soap_type_id',
        'expense_type_id',
        'expense_reason_id',
        'establishment_id',
        'supplier_id',
        'currency_type_id',
        'external_id',
        'state_type_id',
        'number',
        'date_of_issue',
        'time_of_issue',
        'supplier',
        'exchange_rate_sale',
        'total',
        'filename',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'user_id' => 'int',
        'expense_type_id' => 'int',
        'establishment_id' => 'int',
        'supplier_id' => 'int',
        'expense_reason_id' => 'int',
        'exchange_rate_sale' => 'float',
        'total' => 'float'
    ];

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getSupplierAttribute($value)
    {
        return (null === $value) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setSupplierAttribute($value)
    {
        $this->attributes['supplier'] = (null === $value) ? null : json_encode($value);
    }

    /**
     * @return BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Person::class, 'supplier_id');
    }

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function expense_reason()
    {
        return $this->belongsTo(ExpenseReason::class);
    }

    /**
     * @return BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function expense_type()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(ExpensePayment::class);
    }

    /**
     * @param Builder $query
     *
     * @return Builder|null
     */
    public function scopeWhereTypeUser(Builder $query, $params = [])
    {
        if (isset($params['user_id'])) {
            $user_id = (int)$params['user_id'];
            $user = User::find($user_id);
            if (!$user) {
                $user = new User();
            }
        } else {
            $user = auth()->user();
        }
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function getNumberFullAttribute()
    {
        return $this->number;
    }

    /**
     * @return BelongsTo
     */
    public function document_type()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereStateTypeAccepted(Builder $query)
    {
        return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
    }


    /**
     * @return HasMany
     */
    public function expense_items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    /**
     * @return HasMany
     */
    public function expense_payments()
    {
        return $this->hasMany(ExpensePayment::class);
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
     * Obtener relaciones necesarias o aplicar filtros para reporte pagos - finanzas
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterRelationsGlobalPayment($query)
    {
        return $query->with([
            'document_type' => function ($q) {
                $q->select('id', 'description');
            },
        ]);
    }

}
