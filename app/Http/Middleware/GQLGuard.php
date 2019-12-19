<?php

namespace App\Http\Middleware;

use Closure;

class GQLGuard
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
        // \info("GQL request:", $request->all());
        return $next($request);
    }
}
