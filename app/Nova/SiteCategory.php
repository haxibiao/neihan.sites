<?php

namespace App\Nova;

use App\Nova\Actions\StickyToSite;
use Illuminate\Http\Request;
use Haxibiao\Cms\Nova\Actions\AssignToSite;
use Haxibiao\Cms\Nova\SiteCategory as NovaSiteCategory;

class SiteCategory extends NovaSiteCategory
{
    public static $model  = 'App\SiteCategory';

    public function actions(Request $request)
    {
        return [
            new AssignToSite,
            (new StickyToSite)->withMeta(['type'=>'categories']),
        ];
    }
}
