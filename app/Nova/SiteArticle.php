<?php

namespace App\Nova;

use Haxibiao\Cms\Nova\SiteArticle as NovaSiteArticle;

class SiteArticle extends NovaSiteArticle
{
    public static $perPageOptions = [50, 100, 500, 1000];
}
