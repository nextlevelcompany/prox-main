<?php

namespace App\Http\Controllers\System\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Modules\System\Models\Client;
use Modules\System\Models\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('system.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $subdomain = 'demo';
        $fqdn = sprintf('%s.%s', $subdomain, config('configuration.app_url_base'));
        $website = new Website();
        $website->uuid = sprintf('%s_%s', config('configuration.tenancy_database_prefix'), $subdomain);
        app(WebsiteRepository::class)->create($website);
        $hostname = new Hostname();
        $hostname->fqdn = $fqdn;
        $hostname = app(HostnameRepository::class)->create($hostname);
        app(HostnameRepository::class)->attach($hostname, $website);

        Client::query()->create([
            'hostname_id' => $hostname->id,
            'subdomain' => $subdomain,
            'number' => '10417844398',
            'name' => 'CARLOS ERIQUE GASPAR',
            'email' => 'eriquegasparcarlos@gmail.com',
        ]);

        return redirect(RouteServiceProvider::HOME);
    }
}
