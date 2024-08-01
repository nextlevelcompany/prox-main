<?php

namespace Modules\Company\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\Establishment\Models\Establishment;
use Modules\Company\Models\Company;
use Modules\Person\Models\Person;
use App\Models\Tenant\PaymentMethodType;
use Modules\Finance\Traits\FinanceTrait;

class CompanyController extends Controller
{
    use FinanceTrait;

    public function record(Request $request)
    {
        $user = new User();
        if (auth()->user()) {
            $user = auth()->user();
        }

        $establishment_id = $user->establishment_id;
        $establishments = Establishment::without(['country', 'department', 'province', 'district'])->where('id', $establishment_id)->get();
        $series = $user->getSeries();
        $customers = Person::without(['country', 'department', 'province', 'district'])
            ->whereType('customers')
            ->whereIsEnabled()
            ->orderBy('name')
            ->take(200)
            ->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'codigo_tipo_documento_identidad' => $row->identity_document_type_id,
                    'numero_documento' => $row->number,
                    'apellidos_y_nombres_o_razon_social' => $row->name,
                    'codigo_pais' => $row->country_id,
                    'direccion' => $row->address,
                    'correo_electronico' => $row->email,
                    'telefono' => $row->telephone,
                ];

            });
        $payment_method_types = PaymentMethodType::all();
        $payment_destinations = $this->getPaymentDestinations();

        return [
            'series' => $series,
            'establishments' => $establishments,
            'company' => Company::active(),
            'customers' => $customers,
            'payment_method_types' => $payment_method_types,
            'payment_destinations' => $payment_destinations
        ];

    }
}
