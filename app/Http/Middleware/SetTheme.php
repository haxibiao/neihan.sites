<?php


namespace App\Http\Middleware;

use Closure;
use Igaster\LaravelTheme\Facades\Theme;

class SetTheme
{
    public function handle($request, Closure $next){
        $theme = cms_seo_theme();
        if(Theme::exists($theme)){
            Theme::set($theme);
        }
        return $next($request);
    }
}
