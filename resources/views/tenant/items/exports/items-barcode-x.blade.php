<!DOCTYPE html>
<html lang="es">
    <head>
        <style>

        </style>
    </head>
    <body>
        <table width="100%" style="border-spacing: 0px;">
        @if(!empty($record))
            @php
                function withoutRounding($number, $total_decimals) {
                    $number = (string)$number;
                    if($number === '') {
                        $number = '0';
                    }
                    if(strpos($number, '.') === false) {
                        $number .= '.';
                    }
                    $number_arr = explode('.', $number);

                    $decimals = substr($number_arr[1], 0, $total_decimals);
                    if($decimals === false) {
                        $decimals = '0';
                    }

                    $return = '';
                    if($total_decimals == 0) {
                        $return = $number_arr[0];
                    } else {
                        if(strlen($decimals) < $total_decimals) {
                            $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
                        }
                        $return = $number_arr[0] . '.' . $decimals;
                    }
                    return $return;
                }
                $records = collect([]);
                for ($i=0; $i < $stock; $i++) {
                    $records[] = $record;
                }
                $show_price = \Modules\Company\Models\Configuration::first()->isShowPriceBarcodeTicket();
            @endphp
            @foreach($records as $item)
                @switch($format)
                    @case(1)
                        <tr>
                            <td style="text-align: center; font-size: 10px;">
                                <span style="text-transform: uppercase">{{ $item->name }}</span>
                                    <table width="100%" style="text-align: left; font-size: 9px">
                                        <tr>
                                            <td width="50%">MOD: {{ $item->model }}</td>
                                            <td>COD: {{ $item->internal_id }}</td>
                                        </tr>
                                    </table>
                                @include('tenant.items.exports.barcode')
                            </td>
                            <td style="text-align: center; font-size: 11px;">
                                PRECIO<br>
                                {{withoutRounding($record->sale_unit_price, 2)}} {{$record->currency_type->symbol}}
                                {{-- <br><span>{{$loop->iteration}}</span> --}}
                            </td>
                        </tr>

                        @break
                    @case(2)
                        @if($loop->iteration % 2 === 1)
                            <tr>
                        @endif
                            <td width="50%" style="font-size: 9px; text-align: center; padding-left: 5px; padding-right: 5px;">
                                <span style="text-transform: uppercase; font-weight: bold;">{{ $item->name }}</span><br>
                                <table width="100%" style="text-align: left; font-size: 8px; border-spacing: 0px;">
                                    <tr>
                                        <td width="60%">
                                            MOD: {{ $item->model }} <br>
                                            COD: {{ $item->internal_id }}
                                        </td>
                                        @if($show_price)
                                            <td style="text-align: right; font-size: 10px;">
                                                <strong>{{ $item->currency_type->symbol }} {{ round($item->sale_unit_price, 2) }}</strong>
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                                @include('tenant.items.exports.barcode')
                            </td>
                        @if($loop->iteration % 2 === 1)
                            </tr>
                        @endif

                        @break
                    @default
                        @if($loop->iteration % 3 === 1)
                            <tr>
                        @endif
                            <td width="33%" style="text-align: center; padding-top: 12px;">
                                @include('tenant.items.exports.barcode')
                            </td>
                        @if($loop->iteration % 3 === 1)
                            </tr>
                        @endif
                @endswitch
            @endforeach
        @else
            <tr>
                <td>No se encontraron registros.</td>
            </tr>
        @endif
    </table>
    </body>
</html>
