<?php


namespace App;

use App\Traits\CanBeSticky;
use Haxibiao\Cms\Post as BaseSitePost;

class SitePost extends BaseSitePost
{
    use CanBeSticky;
}
