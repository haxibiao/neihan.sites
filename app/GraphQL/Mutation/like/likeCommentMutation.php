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
            'undo'       => ['name' => 'undo', 'type' => Type::boolean()],
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

        $comment = Comment::findOrFail($args['comment_id']);
        if ((isset($args['undo']) && $args['undo']) || session('liked_comment_' . $args['comment_id'])) {
            session()->put('liked_comment_' . $args['comment_id'], 0);
            $comment->likes = $comment->likes - 1;
            $comment->save();

            // delete like comment
            $like = \App\Like::where([
                'user_id'    => $user->id,
                'liked_id'   => $args['comment_id'],
                'liked_type' => 'comments',
            ])->first();
            if ($like) {
                $like->delete();
            }
        } else {
            session()->put('liked_comment_' . $args['comment_id'], 1);
            $comment->likes = $comment->likes + 1;
            $comment->save();

            // save like comment
            $like = \App\Like::firstOrNew([
                'user_id'    => $user->id,
                'liked_id'   => $args['comment_id'],
                'liked_type' => 'comments',
            ]);
            $like->save();

            // record action
            $action = \App\Action::create([
                'user_id'         => $user->id,
                'actionable_type' => 'likes',
                'actionable_id'   => $like->id,
            ]);
        }
        return $comment;
    }
}
