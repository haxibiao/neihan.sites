<?php

namespace App\Http\Middleware;

use Closure;

class TrafficMan
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
        if (!(starts_with($request->path(), 'traffic') ||
            starts_with($request->path(), 'admin'))) {
            $traffic     = new \App\Traffic();
            $traffic->ip = get_ip();

            $agent                  = new \Jenssegers\Agent\Agent();
            $traffic->is_desktop    = $agent->isDesktop();
            $traffic->is_mobile     = $agent->isMobile();
            $traffic->is_phone      = $agent->isPhone();
            $traffic->is_tablet     = $agent->isTablet();
            $traffic->is_wechat     = $agent->match('micromessenger');
            $traffic->is_android_os = $agent->isAndroidOS();
            $traffic->is_robot      = $agent->isRobot();

            $traffic->device   = $agent->device();
            $traffic->platform = $agent->platform();
            $traffic->browser  = $agent->browser();
            $traffic->robot    = $agent->robot();

            $traffic->save();
        }

        return $next($request);
    }
}
