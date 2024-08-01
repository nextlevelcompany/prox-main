<?php

namespace Modules\OrderNote\Observers;

use Illuminate\Support\Str;
use Modules\Company\Models\Company;
use Modules\Establishment\Models\SeriesConfiguration;
use Modules\OrderNote\Models\OrderNote;

class OrderNoteObserver
{
    public function creating(OrderNote $data)
    {
        $company = Company::query()->first();
        $data->user_id = auth()->id();
        $data->external_id = Str::uuid()->toString();
        $data->soap_type_id = $company->soap_type_id;
        $data->state_type_id = '01';
        $data->prefix = 'PD';

        $number = $this->getNewNumber($data);
        $filename = join('-', [$data->series, $number, date('Ymd')]);
        $data->number = $number;
        $data->filename = $filename;
    }

    private function getNewNumber($data)
    {
        if ($data->number === '#') {
            $record = OrderNote::query()
                ->select('id', 'number')
                ->where('soap_type_id', $data->soap_type_id)
                ->where('series', $data->series)
                ->orderBy('number', 'desc')
                ->first();

            if ($record) {
                return $record->number + 1;
            } else {
                $series_configuration = SeriesConfiguration::query()
                    ->where('document_type_id', $data->document_type_id)
                    ->where('series', $data->series)
                    ->first();
                if ($series_configuration) {
                    return $series_configuration->number;
                }
            }
            return 1;
        }

        return $data->number;
    }
}
