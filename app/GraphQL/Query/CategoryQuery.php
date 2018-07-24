<?php

namespace App\GraphQL\Query;

use App\Category;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class CategoryQuery extends Query
{
    protected $attributes = [
        'name' => 'category',
        'description' => 'return a category'
    ];

    public function type()
    {
        return GraphQL::type('Category');
    }

    public function args()
    {
        return [
            'id'          => ['name' => 'id', 'type' => Type::int()],
            'keyword'     => ['name' => 'keyword'   , 'type'    => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $category = Category::findOrFail($args['id']);
        //记录日志
        $category->recordBrowserHistory();  
        return $category;
    }
}
