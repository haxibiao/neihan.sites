<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Cookie;

class GraphQLAuth
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
        $token = !empty($request->header('token')) ? $request->header('token') : $request->get('token');
        if (!empty($token)) {
            $user = User::where('api_token', $token)->first();
            if ($user) {
                //TODO:: 真是APP手机端请求的时候，因为没有Cookie，这个Session也没用
                $request->session()->put('user', $user);
            }
        }

        //此处为了方便UI组浏览器/login-as/{id}切换登录用户调试 gql api
        if (!empty($request->cookie('graphql_user'))) {
            $user = User::find($request->cookie('graphql_user'));
            if ($user) {
                $request->session()->put('user', $user);
            }
        }
        return $next($request);
    }
}
