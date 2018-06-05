<?php

namespace App\GraphQL\Type;

use Carbon\Carbon;
use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class VisitType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Visit',
        'description' => 'A Visit',
    ];

    public function fields()
    {
        return [
            //type
            'type'     => [
                'type'        => Type::string(),
                'description' => 'The video of the Visit',
                'resolve'     => function ($root, $args) {
                    return $root->visited_type;
                },
            ],
            //visited
            'visited'    => [
                'type'        => GraphQL::type('Visited'),
                'description' => 'The Visited item',
                'resolve'     => function ($root, $args) {
                    return $root->visited;
                },
            ],
            //video
            'video'    => [
                'type'        => GraphQL::type('Video'),
                'description' => 'The video of the Visit',
                'resolve'     => function ($root, $args) {
                    return $root->visited;
                },
            ],
            //文章
            'article'  => [
                'type'        => GraphQL::type('Article'),
                'description' => 'The article of the Visit',
                'resolve'     => function ($root, $args) {
                    return $root->visited;
                },
            ],
            //浏览时间
            'time_ago' => [
                'type'        => Type::string(),
                'description' => 'The time_ago of the Visit',
                'resolve'     => function ($root, $args) {
                    $last_datetime = $root->updated_at;
                    $carbon        = Carbon::createFromFormat('Y-m-d H:i:s', $last_datetime);
                    if ($carbon->isToday()) {
                        return str_replace(" ", "", diffForHumansCN($root->updated_at));
                    }
                    return date("m-d H:i", strtotime($root->updated_at));
                },
            ],
        ];
    }
}
