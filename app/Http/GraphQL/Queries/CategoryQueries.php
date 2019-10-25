<?php

namespace App\Http\GraphQL\Queries;


use App\Category;
use Illuminate\Support\Arr;

class CategoryQueries
{
    public function categories($root, array $args, $context){

        $filter = Arr::get($args,'filter');
        //热门
        if($filter == 'hot'){
            return Category::orderBy('count_follows', 'desc')
                ->where('status', 1);
        }
        return Category::orderBy('id', 'desc')
            ->where('status', 1);
    }
}