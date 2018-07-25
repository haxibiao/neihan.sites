<?php
namespace App\GraphQL\Mutation\like;

use App\Comment;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use App\Notifications\ArticleLiked;

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
    //TODO 代码需要抽离
    public function resolve($root, $args)
    {
        $user = getUser();

        $comment = Comment::findOrFail($args['comment_id']);
        if ((isset($args['undo']) && $args['undo']) || session('liked_comment_' . $args['comment_id'])) {
            session()->put('liked_comment_' . $args['comment_id'], 0);

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

            // save like comment
            $like = \App\Like::firstOrNew([
                'user_id'    => $user->id,
                'liked_id'   => $args['comment_id'],
                'liked_type' => 'comments',
            ]);
            $like->save();

            //点赞自己的评论不通知
            if( $user->id != $comment->user_id ){
                $comment->user->notify(new ArticleLiked( $comment->commentable->id, $user->id, $comment));
            }
            // record action
            $action = \App\Action::create([
                'user_id'         => $user->id,
                'actionable_type' => 'likes',
                'actionable_id'   => $like->id,
            ]);
        }
        $comment->likes = $comment->likes()->count(); 
        $comment->save();

        return $comment;
    }
}
