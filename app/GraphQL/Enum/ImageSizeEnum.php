<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ImageSizeEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'ImageSize',
        'description' => 'image size',
        'values' => [
            'SMALL' => 'SMALL',
            'BIG' => 'BIG',
        ],
    ];
}