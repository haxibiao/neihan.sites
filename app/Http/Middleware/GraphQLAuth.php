<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

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
                $request->session()->put('user', $user);
            }
        }
        return $next($request);
    }
}
