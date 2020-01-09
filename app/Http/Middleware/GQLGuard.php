<?php

namespace App\Http\Middleware;

use App\Version;
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
            if ($version = request()->header('version', null)) {
                $user->getProfileAttribute()->update([
                    'app_version' => $version,
                ]);
            }
        }
        return $next($request);
    }
}
