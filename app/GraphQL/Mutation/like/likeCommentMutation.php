<?php
namespace App\GraphQL\Mutation\like;

use App\Comment;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class likeCommentMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'likeCommentMutation',
        'description' => 'like a Comment ',
    ];

    public function type()
    {
        return GraphQL::type('Comment');
    }
 
    public function args()
    {
        return [
            'comment_id' => ['name' => 'comment_id', 'type' => Type::int()],
            'undo'       => [
                'name' => 'undo', 
                'type' => Type::boolean(),
                'defaultValue' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            'comment_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $data = [
            'liked_id'   => $args['comment_id'],
            'liked_type' => 'comments',
            'undo'       => $args['undo']//default false
        ]; 

        $like = new \App\Like();
        return $like->toggleLike($data);

        return $comment;
    }
}
