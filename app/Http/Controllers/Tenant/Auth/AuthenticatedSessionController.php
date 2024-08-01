<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Auth\LoginRequest;
use Modules\Company\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Models\Configuration;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $config = \Modules\System\Models\Configuration::query()->first();
        if (! $config->use_login_global) {
            $config = Configuration::query()->first();
        }
        $useLoginGlobal = $config->use_login_global;
        $login = $config->login;
        $company = Company::query()->first();

        return view('tenant.auth.login', compact('company', 'login', 'useLoginGlobal'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Tenant\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
