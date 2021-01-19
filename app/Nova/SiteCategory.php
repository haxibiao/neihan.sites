<?php

namespace App\Nova;

use Haxibiao\Cms\Nova\SiteCategory as NovaSiteCategory;

class SiteCategory extends NovaSiteCategory
{
    public static $perPageOptions = [50, 100, 500, 1000];
}
