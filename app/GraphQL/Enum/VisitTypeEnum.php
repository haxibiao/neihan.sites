<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class VisitTypeEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'VisitTypeEnum',
        'description' => 'The filters of Visit query',
        'values' => [
            'VIDEO' 	=> 'videos',   
            'ARTICLE' 	=> 'articles',
            'POST'      => 'posts',
            'CATEGORY'  => 'categories',
            'COLLECTION'=> 'collections',
            'USER'		=> 'users',
        ], 
    ];
}