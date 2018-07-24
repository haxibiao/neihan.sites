<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class CommentType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Comment',
        'description' => 'A Comment',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'             => [
                'type'        => Type::int(),
                'description' => 'id of the comment',
            ],
            'commentable_id' => [
                'type'        => Type::int(),
                'description' => 'commentable_id of the comment',
            ],
            'comment_id'     => [
                'type'        => Type::int(),
                'description' => 'comment_id of the comment',
            ],
            'body'           => [
                'type'        => Type::string(),
                'description' => 'body of comment',
                'resolve'     => function ($root, $args) {
                    return $root->wrapperBodyToMobile();
                },
            ],
            'lou'     => [
                'type'        => Type::int(),
                'description' => 'lou of the comment',
            ],
            'time_ago'       => \App\GraphQL\Field\TimeField::class,

            //counts
            'likes'          => [
                'type'        => Type::int(),
                'description' => 'count likes of comment',
            ],
            'liked'          => [
                'type'        => Type::boolean(),
                'description' => 'user is liked current comment',
                'resolve'     => function ($root, $args) {
                    return session('liked_comment_' . $root->id, 0);
                },
            ],

            //relations
            'article'        => [
                'type'        => GraphQL::type('Article'),
                'description' => 'commented article',
                'resolve'     => function ($root, $args) {
                    return $root->commentable;
                },
            ],
            'commented'      => [
                'type'        => GraphQL::type('Comment'),
                'description' => '被评论的评论',
                'resolve'     => function ($root, $args) {
                    return $root->commented;
                },
            ],
            'replyComments'  => [
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'type'        => Type::listOf(GraphQL::type('Comment')),
                'description' => '楼中楼的那些回复',
                'resolve'     => function ($root, $args) {
                    $qb = $root->replyComments();
                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 1000;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    return $qb->get();
                },
            ],
            'user'           => [
                'type'        => GraphQL::type('User'),
                'description' => 'user who made this comment',
                'resolve'     => function ($root, $args) {
                    return $root->user;
                },
            ],
        ];
    }

}
