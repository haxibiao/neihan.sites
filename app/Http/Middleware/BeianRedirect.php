<?php

namespace App\Http\Middleware;

use Closure;

class BeianRedirect
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
        if (starts_with($request->path(), 'article/')) {
            return redirect()->to('http://dongdianyi.haxibiao.com/' . $request->path());
        }
        return $next($request);
    }
}
