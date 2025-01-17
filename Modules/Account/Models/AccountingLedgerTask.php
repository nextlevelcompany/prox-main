<?php

namespace Modules\Account\Models;

use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;

class AccountingLedgerTask extends ModelTenant
{
    protected $table = 'accounting_ledger_task';
    protected $perPage = 25;

    protected $fillable = [
        'month',
        'year',
        'last_rum'
    ];

    protected $casts = [
        'month' => 'int',
        'year' => 'int'
    ];

    protected $dates = [
        'last_rum'
    ];

    protected static function boot()
    {

        parent::boot();

        static::saving(function (self $model) {
            $model->last_rum = Carbon::now();
        });

    }

    /**
     * @return int|null
     */
    public function getMonth(): ?int
    {
        return $this->month;
    }

    /**
     * @param int|null $month
     *
     * @return AccountingLedgerTask
     */
    public function setMonth(?int $month): AccountingLedgerTask
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     *
     * @return AccountingLedgerTask
     */
    public function setYear(?int $year): AccountingLedgerTask
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return Carbon|null
     */
    public function getLastRum(): ?Carbon
    {
        return $this->last_rum;
    }

    /**
     * @param Carbon|null $last_rum
     *
     * @return AccountingLedgerTask
     */
    public function setLastRum(?Carbon $last_rum): AccountingLedgerTask
    {
        $this->last_rum = $last_rum;
        return $this;
    }
}
