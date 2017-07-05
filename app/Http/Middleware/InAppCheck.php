<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class InAppCheck
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
        if ($request->get('inapp')) {
            Cookie::queue('is_in_app', true, 60 * 24 * 365);
        }
        if ($request->get('outapp')) {
            Cookie::queue(Cookie::forget('is_in_app'));
        }
        return $next($request);
    }
}
