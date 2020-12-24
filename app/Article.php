<?php

namespace App;

use App\Traits\CanBeLiked;
use Haxibiao\Cms\Article as CmsArticle;

class Article extends CmsArticle
{
    use CanBeLiked;
}
