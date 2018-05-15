<?php

namespace App\GraphQL\Field;

use Folklore\GraphQL\Support\Field;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ImageField extends Field
{

    protected $attributes = [
        'description' => 'A image',
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [
            'size' => [
                'type'        => GraphQL::type('ImageSize'),
                'description' => 'The size of image',
            ],
        ];
    }

    protected function resolve($root, $args)
    {
        $size = isset($args['size']) ? 　 $args['size'] : 'SMALL';
        return $size == 'SMALL' ? $root->path_small() : $root->path();
    }

}
