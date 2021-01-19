<?php

namespace App\Nova;

use Haxibiao\Cms\Nova\SiteMovie as NovaSiteMovie;

class SiteMovie extends NovaSiteMovie
{
    public static $perPageOptions = [50, 100, 500, 1000];
}
