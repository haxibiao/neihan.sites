<?php
namespace App\GraphQL\Mutation\like;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class likeArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'likeArticleMutation',
        'description' => 'like a Article ',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'article_id' => ['name' => 'article_id', 'type' => Type::int()],
            'undo'       => ['name' => 'undo', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'article_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $article = Article::findOrFail($args['article_id']);
        if ((isset($args['undo']) && $args['undo']) || session('liked_article_' . $args['article_id'])) {
            session()->put('liked_article_' . $args['article_id'], 0);

            // delete like Article
            $like = \App\Like::where([
                'user_id'    => $user->id,
                'liked_id'   => $args['article_id'],
                'liked_type' => 'articles',
            ])->first();
            if ($like) {
                $like->delete();
            }
        } else {
            session()->put('liked_article_' . $args['article_id'], 1);

            // save like Article
            $like = \App\Like::firstOrNew([
                'user_id'    => $user->id,
                'liked_id'   => $args['article_id'],
                'liked_type' => 'articles',
            ]);
            $like->save();

            // record action
            $action = \App\Action::create([
                'user_id'         => $user->id,
                'actionable_type' => 'likes',
                'actionable_id'   => $like->id,
            ]);
        }
        $article->count_likes = $article->likes()->count();
        $article->save();
        return $article;
    }
}
