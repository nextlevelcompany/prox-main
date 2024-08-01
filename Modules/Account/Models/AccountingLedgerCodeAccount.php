<?php

namespace Modules\Account\Models;

use App\Models\Tenant\ModelTenant;

class AccountingLedgerCodeAccount extends ModelTenant
{
    protected $table = 'cat_accounting_ledger_code_account';
    protected $perPage = 25;

    protected $casts = [
        'disabled' => 'int'
    ];

    protected $fillable = [
        'code_account',
        'name',
        'disabled'
    ];

    public static function getNameByCodeAcount($code, $fallback = '')
    {
        $record = self::where('code_account', $code)->first();
        if ($record !== null) {
            return $record->name;
        }
        return $fallback;

    }
}
