<?php

namespace App\GraphQL\Query;

use App\Collection;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class CollectionQuery extends Query
{
    protected $attributes = [
        'name' => 'Collection',
        'description' => 'return a Collection'
    ];

    public function type()
    {
        return GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        return Collection::findOrFail($args['id']);
    }
}
