<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

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
        if ($request->get('in_app')) {
            Cookie::queue('is_in_app', true, 60 * 24 * 365);
            //autologin with app user_id
            if($request->get('user_id')) {
                $user = User::find($request->get('user_id'));
                if($user){
                    Auth::login($user,1);
                }
            }
        }
        if ($request->get('outapp')) {
            Cookie::queue(Cookie::forget('is_in_app'));
        }
        return $next($request);
    }
}
