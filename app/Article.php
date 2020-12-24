<?php

namespace App;

use App\Traits\CanBeLiked;
use Haxibiao\Cms\Model\Article as CmsArticle;

class Article extends CmsArticle
{
    use CanBeLiked;
}
