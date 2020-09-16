<?php

namespace App;

use App\Traits\CanBeLiked;
use Haxibiao\Content\Article as BaseArticle;

class Article extends BaseArticle
{
    use CanBeLiked;
}
