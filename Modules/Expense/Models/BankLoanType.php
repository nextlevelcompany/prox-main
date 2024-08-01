<?php

namespace Modules\Expense\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankLoanType extends ModelTenant
{
    protected $fillable = [
        'description'
    ];

    /**
     * @return HasMany
     */
    public function bank_loans()
    {
        return $this->hasMany(BankLoan::class);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return BankLoanType
     */
    public function setDescription(string $description): BankLoanType
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection|BankLoan[]
     */
    public function getBankLoans()
    {
        return $this->bank_loans;
    }

    /**
     * @param Collection|BankLoan[] $bank_loans
     *
     * @return BankLoanType
     */
    public function setBankLoans($bank_loans)
    {
        $this->bank_loans = $bank_loans;
        return $this;
    }

}

