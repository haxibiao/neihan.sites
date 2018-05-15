<?php

namespace App\GraphQL\Field;

use Folklore\GraphQL\Support\Field;
use GraphQL\Type\Definition\Type;

class TimeField extends Field
{

    protected $attributes = [
        'description' => 'A time field',
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [];
    }

    protected function resolve($root, $args)
    {
        return $root->timeAgo();
    }

}
