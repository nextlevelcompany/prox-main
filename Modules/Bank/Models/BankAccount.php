<?php

namespace Modules\Bank\Models;

use Modules\Catalog\Models\CurrencyType;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Expense\Models\BankLoan;
use Modules\Expense\Models\BankLoanItem;
use Modules\Finance\Models\GlobalPayment;

class BankAccount extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'bank_id',
        'description',
        'number',
        'currency_type_id',
        'cci',
        'status',
        'initial_balance',
        'show_in_documents',
        'establishment_id',
    ];

    protected $casts = [
        'bank_id' => 'int',
        'status' => 'int',
        'initial_balance' => 'float',
        'show_in_documents' => 'bool'
    ];

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeSelectIdDescription($query)
    {
        $query->select(
            'id',
            'description'
        )->orderBy('description');
        return $query;
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
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    /**
     * @return MorphMany
     */
    public function global_destination()
    {
        return $this->morphMany(GlobalPayment::class, 'destination')->with(['payment']);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function getShowInDocumentsAttribute($value)
    {
        return $value ? true : false;
    }

    /**
     * Devuelve las cuentas bancarias que esten activas (status 1) y
     * que deban imprimirse en los documentos (show_in_documents 1)
     *
     * @return Builder
     */
    public function scopePrintShowInDocuments($query)
    {
        return $query->where('status', 1)->where('show_in_documents', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bank_loan()
    {
        return $this->hasMany(BankLoan::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bank_loan_items()
    {
        return $this->hasManyThrough(
            BankLoanItem::class,
            BankLoan::class,
            'bank_account_id',
            'id'

        )->whereIn('bank_loans.state_type_id', ['01', '03', '05', '07', '13']);
    }


    /**
     *
     * Se agrega scope polimorfico para filtrar destino en global payment
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithBankIfExist($query)
    {
        return $query->with(['bank']);
    }


}
