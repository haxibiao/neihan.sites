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
            'at_uid'         => ['name' => 'at_uid', 'type' => Type::int()],
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
        $user = getUser();

        $comment = Comment::create([
            'user_id'          => $user->id,
            'commentable_id'   => $args['commentable_id'],
            'body'             => $args['body'],
            'commentable_type' => 'articles',
        ]);

        if (isset($args['at_uid'])) {
            $comment->at_uid = $args['at_uid'];
            //TODO:: notify at user
            $atUser = \App\User::findOrFail($args['at_uid']);
            // $atUser->notify(new \App\Notifications\CommentReplied($comment));
        }

        if (isset($args['comment_id'])) {
            $comment->comment_id = $args['comment_id'];
            $commented           = Comment::find($comment->comment_id);
            if ($commented) {
                $comment->lou = $commented->lou;
                //如果回复的是子评论， 对应的comment_id应该和他一样，同属于一个评论楼层的楼中楼回复
                if ($commented->comment_id) {
                    $comment->comment_id = $commented->comment_id;
                }
            }
        } else {
            $comment->lou = Comment::where('commentable_id', $args['commentable_id'])
                ->where('comment_id', null)
                ->where('commentable_type', 'articles')
                ->count() + 1;
        }

        $comment->save();

        //record action while comment on article
        $action = \App\Action::create([
            'user_id'         => $user->id,
            'actionable_type' => 'comments',
            'actionable_id'   => $comment->id,
        ]);

        //notify article author
        $article                 = \App\Article::findOrFail($args['commentable_id']);
        $article->count_replies  = $article->comments()->count();
        $article->count_comments = $article->comments()->max('lou');
        $article->commented      = \Carbon\Carbon::now();
        $article->save();
        $article->user->notify(new \App\Notifications\ArticleCommented($article, $comment, $user));

        return $comment;
    }
}
