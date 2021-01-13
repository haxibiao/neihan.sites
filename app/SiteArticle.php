<?php

namespace App;

use App\Traits\CanBeSticky;
use Haxibiao\Cms\Article as BaseSiteArticle;

class SiteArticle extends BaseSiteArticle
{
    use CanBeSticky;
}
