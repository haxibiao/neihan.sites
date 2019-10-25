<?php
namespace App\GraphQL\Mutation\comment;

use App\Comment;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class addCommentMutation extends Mutation
{

    protected $attributes = [
        'name'        => 'addCommentMutation',
        'description' => 'create a Comment ',
    ];

    public function type()
    {
        return GraphQL::type('Comment');
    }

    public function args()
    {
        return [
            'commentable_id' => ['name' => 'commentable_id', 'type' => Type::int()],
            'body'           => ['name' => 'body', 'type' => Type::string()],
            'comment_id'     => ['name' => 'comment_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'commentable_id' => ['required'],
            'body'           => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $comment                  = new Comment();
        $args['commentable_type'] = 'articles';

        $comment = $comment->store($args);

        //matomo:记录action 发表评论, 在model层 已记录

        return $comment;
    }
}
