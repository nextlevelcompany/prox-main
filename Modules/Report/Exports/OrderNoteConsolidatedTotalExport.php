<?php

namespace Modules\Report\Exports;

use Modules\Establishment\Models\Establishment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Company\Models\Company;


/**
 * Class OrderNoteConsolidatedTotalExport
 *
 * @package Modules\Report\Exports
 */
class OrderNoteConsolidatedTotalExport implements FromView, ShouldAutoSize
{
    use Exportable;

    /**
     * @var \Illuminate\Support\Collection $records
     *
     */
    public $records;
    public $company;
    public $establishment;
    public $params;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getRecords()
    : \Illuminate\Support\Collection {
        return $this->records;
    }

    /**
     * @param \Illuminate\Support\Collection $records
     *
     * @return OrderNoteConsolidatedTotalExport
     */
    public function setRecords(\Illuminate\Support\Collection $records)
    : OrderNoteConsolidatedTotalExport {
        $this->records = $records;
        return $this;
    }

    /**
     * @return Company
     */
    public function getCompany()
    : Company {
        return $this->company;
    }

    /**
     * @param Company $company
     *
     * @return OrderNoteConsolidatedTotalExport
     */
    public function setCompany(Company $company)
    : OrderNoteConsolidatedTotalExport {
        $this->company = $company;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    : array {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return OrderNoteConsolidatedTotalExport
     */
    public function setParams(array $params)
    : OrderNoteConsolidatedTotalExport {
        $this->params = $params;
        return $this;
    }

    public function getEstablishment()
    : Establishment {
        return $this->establishment;
    }

    public function setEstablishment(Establishment $establishment)
    : OrderNoteConsolidatedTotalExport {
        $this->establishment = $establishment;
        return $this;
    }


    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View {
        return view('report::order_notes_consolidated.report_excel_totals', [
            'records'=> $this->records,
            'company' => $this->company,
            'establishment'=>$this->establishment,
            'params'=>$this->params
        ]);
    }

}
