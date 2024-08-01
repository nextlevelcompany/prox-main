<?php

namespace App\Http\Middleware;

use Closure;
use Modules\Company\Models\Company;

class InternalModeTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $configuration = Company::first();

        if($configuration->soap_type_id == '03'){
            abort(403);
        }

        return $next($request);
    }
}
