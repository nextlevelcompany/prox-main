<?php

namespace Modules\Account\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportAccountingSumeriusExport implements FromView
{
    use Exportable;

    protected $data;

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    public function view(): View
    {
        return view('tenant.account.accounting.templates.excel_sumerius', $this->data);
    }
}
