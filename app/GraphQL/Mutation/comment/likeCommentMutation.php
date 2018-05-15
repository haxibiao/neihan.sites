<?php
namespace App\GraphQL\Mutation\comment;

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
        $user = getUser();

        $comment = Comment::find($args['comment_id']);
        if (session('liked_comment_' . $args['comment_id'], 0)) {
            session()->put('liked_comment_' . $args['comment_id'], 0);
            if ($comment) {
                $comment->likes = $comment->likes - 1;
                $comment->save();
            }
        } else {
            session()->put('liked_comment_' . $args['comment_id'], 1);
            if ($comment) {
                $comment->likes = $comment->likes + 1;
                $comment->save();
            }
        }
        return $comment;
    }
}
