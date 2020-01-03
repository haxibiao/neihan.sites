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
        $user = checkUser();
        if($user){
            $profile = $user->profile;
            $profile->app_version  = request()->header('version', null);
            $profile->save(['timestamps' => false]);
        }
        return $next($request);
    }
}
