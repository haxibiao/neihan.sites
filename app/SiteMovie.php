<?php


namespace App;

use App\Traits\CanBeSticky;
use Haxibiao\Cms\Movie as BaseSiteMovie;

class SiteMovie extends BaseSiteMovie
{
    use CanBeSticky;
}
