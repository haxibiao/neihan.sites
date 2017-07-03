<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthEditor
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
        if (!Auth::user()->is_editor) {
            $request->session()->flash('message', '需要本站编辑身份!');
            // dd(session('message'));
            return abort(403);
        }
        return $next($request);
    }
}
