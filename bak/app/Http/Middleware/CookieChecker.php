<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class CookieChecker
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
        $this->check_is_in_weixin_editing($request);
        $this->check_is_in_app($request);
        return $next($request);
    }

    public function check_is_in_weixin_editing($request)
    {
        if ($request->get('in_weixin')) {
            Cookie::queue('is_in_weixin_editing', true);
        } else {
            Cookie::forget('is_in_weixin_editing');
        }
    }

    public function check_is_in_app($request)
    {
        if ($request->get('in_app')) {
            Cookie::queue('is_in_app', true, 60 * 24 * 365);
            //autologin with app user_id
            if ($request->get('user_id')) {
                $user = User::find($request->get('user_id'));
                if ($user) {
                    Auth::login($user, 1);
                }
            }
        }
        if ($request->get('outapp')) {
            Cookie::queue(Cookie::forget('is_in_app'));
        }
    }
}
