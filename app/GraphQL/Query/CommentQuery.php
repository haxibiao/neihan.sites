<?php

namespace App\GraphQL\Query;

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use \App\Comment;

class CommentQuery extends Query
{
    protected $attributes = [
        'name' => 'CommentQuery',
        'description' => 'A query'
    ];

    public function type()
    {
        return GraphQL::type('Comment');
    }

    public function args()
    {
        return [
            'comment_id' => [
                'name' => 'comment_id', 
                'type' => Type::int()
            ],
        ];
    } 
    //取出整个楼
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $comment = Comment::find($args['comment_id']);
        //不是楼中楼
        if(empty($comment->comment_id)){
            return $comment; 
        }
        return Comment::find($comment->comment_id);
    }
}
