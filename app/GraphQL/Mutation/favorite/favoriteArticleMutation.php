<?php
namespace App\GraphQL\Mutation\favorite;

use App\Article;
use App\Favorite;
use App\Action;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class favoriteArticleMutation extends Mutation
{
    protected $attributes = [ 
        'name'        => 'favoriteArticleMutation', 
        'description' => 'toggle 收藏/取消收藏 文章',
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
        $user   = getUser();
        $article = Article::findOrFail($args['article_id']);
        //TOGGLE
        if ((isset($args['undo']) && $args['undo']) || session('favorited_article_' . $args['article_id'])) {
            
            session()->put('favorited_article_' . $args['article_id'], 0);
            //delete favorate Article
            $favorite = Favorite::where([
                'user_id'    => $user->id,
                'faved_id'   => $args['article_id'],
                'faved_type' => 'articles',
            ])->delete();
        } else {
            session()->put('favorited_article_' . $args['article_id'], 1);
            // save favorate Article
            $favorite = Favorite::firstOrCreate([
                'user_id'    => $user->id,
                'faved_id'   => $args['article_id'],
                'faved_type' => 'articles',
            ]);
            //保留操作记录
            $action = Action::firstOrCreate([
                'user_id'         => $user->id,
                'actionable_type' => 'favorites',
                'actionable_id'   => $favorite->id,
            ]);
        }
        //增加文章喜欢数字
        $article->increment('count_favorites', 1);
        return $article;
    }
}
