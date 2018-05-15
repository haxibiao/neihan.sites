<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class QuestionType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Question',
        'description' => 'A Question',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'       => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the question',
            ],
            'title'    => [
                'type'        => Type::string(),
                'description' => 'The title of question',
            ],
            'background'    => [
                'type'        => Type::string(),
                'description' => 'The background of question',
            ],
            'time_ago'      => \App\GraphQL\Field\TimeField::class,
            
        ];
    }
}
