<?php

namespace Modules\Expense\Models;

use Modules\Cash\Models\CashDocument;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Catalog\Models\CardBrand;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\PaymentFile;

class ExpensePayment extends ModelTenant
{
    public $timestamps = false;

    protected $casts = [
        'expense_id' => 'int',
        'expense_method_type_id' => 'int',
        'has_card' => 'bool',
        'date_of_payment' => 'date',
        'payment' => 'float'
    ];

    protected $fillable = [
        'expense_id',
        'date_of_payment',
        'expense_method_type_id',
        'has_card',
        'card_brand_id',
        'reference',
        'payment',
    ];

    /**
     * @return BelongsTo
     */
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    /**
     * @return BelongsTo
     */
    public function expense_method_type()
    {
        return $this->belongsTo(ExpenseMethodType::class);
    }

    /**
     * @return BelongsTo
     */
    public function card_brand()
    {
        return $this->belongsTo(CardBrand::class);
    }

    /**
     * @return MorphOne
     */
    public function global_payment()
    {
        return $this->morphOne(GlobalPayment::class, 'payment');
    }

    /**
     * @return BelongsTo
     */
    public function associated_record_payment()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    /**
     * @return MorphOne
     */
    public function payment_file()
    {
        return $this->morphOne(PaymentFile::class, 'payment');
    }

    /**
     * @return HasMany
     */
    public function cash_documents()
    {
        return $this->hasMany(CashDocument::class);
    }


    /**
     *
     * Obtener gastos en efectivo
     *
     * Los unicos gastos que se registran en caja chica pos, son los que tiene como Método de gasto CAJA GENERAL (se asume en efectivo)
     *
     * @return Collection
     */
    public function getCashPayments()
    {

        $expense_payments = collect();

        return $expense_payments->push([

            'type' => 'expense_payment',
            'type_transaction' => 'egress',
            'type_transaction_description' => 'Gasto',
            'date_of_issue' => $this->associated_record_payment->date_of_issue->format('Y-m-d'),
            'number_full' => $this->associated_record_payment->number_full,
            'acquirer_name' => $this->associated_record_payment->supplier->name,
            'acquirer_number' => $this->associated_record_payment->supplier->number,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->expense_type->description,
            'expense_method_type_id' => $this->expense_method_type_id,
            'payment' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->payment,

        ]);

    }


    /**
     *
     * Obtener relaciones necesarias o aplicar filtros para reporte pagos - finanzas
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterRelationsPayments($query)
    {
        return $query->with([
            'expense_method_type' => function ($q) {
                $q->select('id', 'description');
            },
        ]);
    }


    /**
     *
     * Tipo de transaccion para caja
     *
     * @return string
     */
    public function getTransactionTypeCash()
    {
        return 'egress';
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
            'apply' => false,
        ];
    }


    /**
     *
     * Obtener informacion del pago y registro origen relacionado
     *
     * @return array
     */
    public function getRowResourceCashPayment()
    {
        return [
            'type' => 'expense_payment',
            'type_transaction' => 'egress',
            'type_transaction_description' => 'Gasto',
            'date_of_issue' => $this->associated_record_payment->date_of_issue->format('Y-m-d'),
            'number_full' => $this->associated_record_payment->number_full,
            'acquirer_name' => $this->associated_record_payment->supplier->name,
            'acquirer_number' => $this->associated_record_payment->supplier->number,
            'currency_type_id' => $this->associated_record_payment->currency_type_id,
            'document_type_description' => $this->associated_record_payment->expense_type->description,
            'payment_method_type_id' => $this->expense_method_type_id,
            'payment' => $this->associated_record_payment->isVoidedOrRejected() ? 0 : $this->payment,
        ];
    }

}
