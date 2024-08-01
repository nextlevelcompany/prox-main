<?php

use Illuminate\Support\Carbon;
use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\AttributeType;
use Modules\Catalog\Models\ChargeDiscountType;
use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\IdentityDocumentType;
use Modules\Catalog\Models\OperationType;
use Illuminate\Support\Facades\Cache;
use Modules\Catalog\Models\PriceType;
use Modules\Catalog\Models\SystemIscType;

if (!function_exists('func_in_array')) {
    function func_in_array($array = [], $index = '', $value = null)
    {
        if (isset($array[$index])) {
            return $array[$index];
        }
        return $value;
    }
}

if (!function_exists('func_str_to_upper_utf8')) {
    function func_str_to_upper_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtoupper($text, 'utf-8');
    }
}

if (!function_exists('func_str_to_lower_utf8')) {
    function func_str_to_lower_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtolower($text, 'utf-8');
    }
}

if (!function_exists('func_filter_items')) {
    function func_filter_items($query, $text)
    {
        $text_array = explode(' ', $text);
        foreach ($text_array as $txt) {
            $trim_txt = trim($txt);
            $query->where('text_filter', 'like', "%$trim_txt%");
        }

        return $query;
    }
}

if (!function_exists('func_get_table_locations')) {
    function func_get_table_locations()
    {
        if (Cache::has('table_locations')) {
            return Cache::get('table_locations');
        }

        $locations = [];
        $departments = Department::query()
            ->with('provinces', 'provinces.districts')
            ->get();
        foreach ($departments as $department) {
            $children_provinces = [];
            foreach ($department->provinces as $province) {
                $children_districts = [];
                foreach ($province->districts as $district) {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => func_str_to_upper_utf8($district->id . " - " . $district->description)
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => func_str_to_upper_utf8($province->description),
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => func_str_to_upper_utf8($department->description),
                'children' => $children_provinces
            ];
        }

        Cache::put('table_locations', $locations, 1440);

        return $locations;
    }
}

if (!function_exists('func_get_table_countries')) {
    function func_get_table_countries()
    {
        if (Cache::has('table_countries')) {
            return Cache::get('table_countries');
        }

        $records = Country::query()
            ->get();

        Cache::put('table_countries', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_operation_types')) {
    function func_get_table_operation_types()
    {
        if (Cache::has('table_operation_types')) {
            return Cache::get('table_operation_types');
        }

        $records = OperationType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_operation_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_affectation_igv_types')) {
    function func_get_table_affectation_igv_types()
    {
        if (Cache::has('table_affectation_igv_types')) {
            return Cache::get('table_affectation_igv_types');
        }

        $records = AffectationIgvType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_affectation_igv_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_identity_document_types')) {
    function func_get_table_identity_document_types()
    {
        if (Cache::has('table_identity_document_types')) {
            return Cache::get('table_identity_document_types');
        }

        $records = IdentityDocumentType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_identity_document_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_currency_types')) {
    function func_get_table_currency_types()
    {
        if (Cache::has('table_currency_types')) {
            return Cache::get('table_currency_types');
        }

        $records = CurrencyType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_currency_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_attribute_types')) {
    function func_get_table_attribute_types()
    {
        if (Cache::has('table_attribute_types')) {
            return Cache::get('table_attribute_types');
        }

        $records = AttributeType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_attribute_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_system_isc_types')) {
    function func_get_table_system_isc_types()
    {
        if (Cache::has('table_system_isc_types')) {
            return Cache::get('table_system_isc_types');
        }

        $records = SystemIscType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_system_isc_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_price_types')) {
    function func_get_table_price_types()
    {
        if (Cache::has('table_price_types')) {
            return Cache::get('table_price_types');
        }

        $records = PriceType::query()
            ->where('active', true)
            ->get();

        Cache::put('table_price_types', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_discount_types_item')) {
    function func_get_table_discount_types_item()
    {
        if (Cache::has('table_discount_types_item')) {
            return Cache::get('table_discount_types_item');
        }

        $records = ChargeDiscountType::query()
            ->where('type', 'discount')
            ->where('level', 'item')
            ->where('active', true)
            ->get();

        Cache::put('table_discount_types_item', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_table_charge_types_item')) {
    function func_get_table_charge_types_item()
    {
        if (Cache::has('table_charge_types_item')) {
            return Cache::get('table_charge_types_item');
        }

        $records = ChargeDiscountType::query()
            ->where('type', 'charge')
            ->where('level', 'item')
            ->where('active', true)
            ->get();

        Cache::put('table_charge_types_item', $records, 1440);

        return $records;
    }
}

if (!function_exists('func_get_new_dates')) {
    function func_get_new_dates($data)
    {
        $period = $data['period'];
        $date_start = $data['date_start'];
        $date_end = $data['date_end'];
        $month_start = $data['month_start'];
        $month_end = $data['month_end'];

        $d_start = null;
        $d_end = null;

        switch ($period) {
            case 'month':
                $m = (int)$month_start['month'] + 1;
                $d_start = Carbon::parse($month_start['year'] . '-' . $m . '-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start['year'] . '-' . $m . '-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $m_start = (int)$month_start['month'] + 1;
                $m_end = (int)$month_end['month'] + 1;

                $d_start = Carbon::parse($month_start['year'] . '-' . $m_start . '-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end['year'] . '-' . $m_end . '-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        return [
            'date_start' => $d_start,
            'date_end' => $d_end,
        ];
    }
}

if (!function_exists('func_is_windows')) {
    function func_is_windows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
