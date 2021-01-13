<?php


namespace App;

use App\Traits\CanBeSticky;
use Haxibiao\Cms\Category as BaseSiteCategory;

class SiteCategory extends BaseSiteCategory
{
    use CanBeSticky;
}
