<?php

namespace App\Nova;

use App\Nova\Actions\StickyToSite;
use Haxibiao\Cms\Nova\Actions\AssignToSite;
use Haxibiao\Cms\Nova\SiteArticle as NovaSiteArticle;
use Illuminate\Http\Request;

class SiteArticle extends NovaSiteArticle
{
    public static $model  = 'App\SiteArticle';

    public function actions(Request $request)
    {
        return [
            new AssignToSite,
            (new StickyToSite)->withMeta(['type'=>'articles']),
        ];
    }
}
