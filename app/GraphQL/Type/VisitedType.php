<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class VisitedType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Visited',
        'description' => 'A Visited',
    ];

    public function fields()
    {
        return [
            'id'         => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Visited',
            ],
            'title'   => [
                'type'        => Type::string(),
                'description' => 'The title of Visited',
            ],
            'description' => [
                'type'        => Type::string(),
                'description' => 'The description of Visited',
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,
        ];
    }
}
