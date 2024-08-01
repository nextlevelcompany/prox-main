<?php

namespace Modules\Expense\Models;

use App\Models\Tenant\ModelTenant;

class BankLoanReason extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'description'
    ];

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
     * @return BankLoanReason
     */
    public function setDescription(string $description): BankLoanReason
    {
        $this->description = $description;
        return $this;
    }

}

