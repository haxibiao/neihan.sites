<?php

namespace App\Http\Middleware;

use Closure;
use \Carbon\Carbon;

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

            $traffic->path    = $request->path();
            $traffic->referer = $request->server('HTTP_REFERER');
            if ($traffic->referer) {
                $traffic->referer_domain = parse_url($traffic->referer)['host'];
            }

            $traffic->date = Carbon::now()->format('Y-m-d');

            $traffic->year  = Carbon::now()->year;
            $traffic->month = Carbon::now()->month;
            $traffic->day   = Carbon::now()->day;

            $traffic->dayOfWeek   = Carbon::now()->dayOfWeek;
            $traffic->dayOfYear   = Carbon::now()->dayOfYear;
            $traffic->daysInMonth = Carbon::now()->daysInMonth;
            $traffic->weekOfMonth = Carbon::now()->weekOfMonth;
            $traffic->weekOfYear  = Carbon::now()->weekOfYear;

            if (starts_with($traffic->path, 'article/')) {
                $article_id = str_replace('article/', '', $traffic->path);
                if (is_numeric($article_id)) {
                    $traffic->article_id = $article_id;
                    $article             = \App\Article::with('category')->find($article_id);
                    if ($article) {
                        if ($article->category) {
                            $traffic->category = $article->category->name;
                        }
                    }
                }
            }

            $traffic->save();
        }

        return $next($request);
    }
}
