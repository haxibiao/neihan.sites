<?php

namespace App;

use App\Traits\CategoryRepo;
use App\Traits\CategoryResolvers;
use  Haxibiao\Question\Category as BaseCategory;

class Category extends BaseCategory
{
    use CategoryRepo;

    // resolvers
    public function getByType($rootValue, array $args,  $context, $resolveInfo)
    {
        $category = self::where("type", $args['type'])->where("status", 1)->orderBy("order", "desc");
        return $category;
    }
}
