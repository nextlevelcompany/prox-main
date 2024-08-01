<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Company\Models\Company;

class AuthController extends Controller
{
    public function getData()
    {
        $company = Company::query()->first();
        $logo = asset('logo/700x300.jpg');
        if ($company->logo) {
            $logo = asset('storage/uploads/logos/' . $company->logo);
        }

        $user = auth()->user();

        $menu = [
            ['title' => 'Dashboard', 'link' => '/dashboard', 'icon' => 'dashboard'],
            ['title' => 'Clientes', 'links' => [
                ['title' => 'Clientes', 'link' => '/addon_persons'],
                ['title' => 'Tipos de Clientes', 'link' => '/person-types'],
            ], 'icon' => 'perm_identity'],
        ];
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
            ],
            'logo' => $logo,
            'menu' => $menu
        ];
    }
}
