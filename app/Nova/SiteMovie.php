<?php

namespace App\Nova;

use App\Nova\Actions\StickyToSite;
use Illuminate\Http\Request;
use Haxibiao\Cms\Nova\Actions\AssignToSite;
use Haxibiao\Cms\Nova\SiteMovie as NovaSiteMovie;

class SiteMovie extends NovaSiteMovie
{
    public static $model  = 'App\SiteMovie';

    public function actions(Request $request)
    {
        return [
            new AssignToSite,
            (new StickyToSite)->withMeta(['type'=>'movies']),
        ];
    }
}
