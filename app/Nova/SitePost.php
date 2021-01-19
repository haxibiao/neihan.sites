<?php

namespace App\Nova;

use Haxibiao\Cms\Nova\SitePost as NovaSitePost;

class SitePost extends NovaSitePost
{
    public static $perPageOptions = [50, 100, 500, 1000];
}
