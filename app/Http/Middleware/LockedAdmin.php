<?php

namespace App\Http\Middleware;

use Closure;
use Modules\System\Models\Configuration;

class LockedAdmin
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
        $configuration = Configuration::first();

        if($configuration->locked_admin){
            abort(403);
        }

        return $next($request);
    }
}
