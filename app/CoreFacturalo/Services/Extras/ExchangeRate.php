<?php

namespace App\CoreFacturalo\Services\Extras;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class ExchangeRate
{
    protected $client;

    public function __construct()
    {

    }
    private function search($month, $year)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.sunat.gob.pe/a/txt/tipoCambio.txt',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);


            if ($response != "") {
                $html = $response;

                $explode = explode('|', $html);

                $values[] = [
                    (int)substr($html,0,2),
                    $explode[1],
                    $explode[2]
                ];

                return collect($values)->toArray();
            }

        } catch (Exception $e) {
            // dd($e);
            Log::info("Error consulta T/C: ".$e->getMessage());
            return false;

        }

        return false;
    }

    public function searchDate($date)
    {
        $date = Carbon::parse($date);
        $res = $this->searchByDay($date);
        $date = $date->addDay(-1);

        if(!$res){
            $res = $this->searchByDay($date);
            $date = $date->addDay(-1);
        }

        if(!$res){
            $res = $this->searchByDay($date);
            $date = $date->addDay(-1);
        }

        if(!$res){
            $res = $this->searchByDay($date);
            $date = $date->addDay(-1);
        }

        return $res;
    }

    private function searchByDay($date)
    {

        $day = $date->day;
        $year = $date->year;
        $month = $date->month;
        $exchange_rate = new  ExchangeRate();
        $exchange_rates = $exchange_rate->search($month, $year);
        if($exchange_rates) {
            foreach ($exchange_rates as $row)
            {
                $new_row = array_values($row);

                if ($new_row[0] == (int)$day) {
                    return [
                        'date_data' => $date->format('Y-m-d'),
                        'data' => [
                            'purchase' => $new_row[1],
                            'sale' => $new_row[2]
                        ]
                    ];
                }
            }
        }

        return false;
    }
}
