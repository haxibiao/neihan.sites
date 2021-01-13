<?php

namespace App\Nova;

use App\Nova\Actions\StickyToSite;
use Haxibiao\Cms\Nova\Actions\AssignToSite;
use Haxibiao\Cms\Nova\SitePost as NovaSitePost;
use Illuminate\Http\Request;

class SitePost extends NovaSitePost
{
    public static $model  = 'App\SitePost';

    public function actions(Request $request)
    {
        return [
            new AssignToSite,
            (new StickyToSite)->withMeta(['type'=>'posts']),
        ];
    }
}
