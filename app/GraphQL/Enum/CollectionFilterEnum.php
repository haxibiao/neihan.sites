<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class CollectionFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CollectionFilter',
        'description' => 'The filters of Collection query',
        'values' => [
            'MINE' => 'MINE',
            'FOLLOWED' => 'FOLLOWED',
        ],
    ];
}