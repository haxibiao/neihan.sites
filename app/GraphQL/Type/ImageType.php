<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ImageType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Image',
        'description' => 'A Image',
    ];

    /*
     * UnImage following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'        => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id of the Image',
            ],
            'width'     => [
                'type'        => Type::int(),
                'description' => 'width of Image',
            ],
            'height'    => [
                'type'        => Type::int(),
                'description' => 'height of Image',
            ],
            'url'       => [
                'type'        => Type::string(),
                'description' => 'url of Image',
                'resolve'     => function ($root, $args) {
                    return $root->url();
                },
            ],
            'url_small' => [
                'type'        => Type::string(),
                'description' => 'url_small of Image',
                'resolve'     => function ($root, $args) {
                    return $root->url_small();
                },
            ],
            'time_ago'  => \App\GraphQL\Field\TimeField::class,

            //relation
            'user'      => [
                'type'        => GraphQL::type('User'),
                'description' => 'user who made this Image',
                'resolve'     => function ($root, $args) {
                    return $root->user;
                },
            ],
        ];
    }

}
