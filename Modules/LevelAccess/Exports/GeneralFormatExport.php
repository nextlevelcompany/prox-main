<?php

namespace Modules\LevelAccess\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GeneralFormatExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view_name($view_name)
    {
        $this->view_name = $view_name;
        return $this;
    }


    public function data($data)
    {
        $this->data = $data;
        return $this;
    }


    public function view(): View
    {
        return view($this->view_name, $this->data);
    }


}
