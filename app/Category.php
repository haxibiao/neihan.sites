<?php

namespace App;

use App\Traits\CategoryAttrs;
use App\Traits\CategoryRepo;
use App\Traits\CategoryResolvers;
use  Haxibiao\Question\Category as BaseCategory;

class Category extends BaseCategory
{
    use CategoryRepo;
    use CategoryAttrs;

    // resolvers
    public function getByType($rootValue, array $args,  $context, $resolveInfo)
    {
        $category = self::where("type", $args['type'])->where("status", 1)->orderBy("rank", "desc");
        return $category;
    }
}
