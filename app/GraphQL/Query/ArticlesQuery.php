<?php

namespace App\GraphQL\Query;

use App\Article;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ArticlesQuery extends Query
{
    protected $attributes = [
        'name' => 'articles',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Article'));
    }

    public function args()
    {
        return [
            'user_id'       => ['name' => 'user_id', 'type' => Type::int()],
            'category_id'   => ['name' => 'category_id', 'type' => Type::int()],
            'collection_id' => ['name' => 'collection_id', 'type' => Type::int()],
            'limit'         => ['name' => 'limit', 'type' => Type::int()],
            'offset'        => ['name' => 'offset', 'type' => Type::int()],
            'filter'        => ['name' => 'filter', 'type' => GraphQL::type('ArticleFilter')],
            'hot_time'      => ['name' => 'hot_time', 'type' => Type::int()],
            'order'         => ['name' => 'order', 'type' => GraphQL::type('ArticleOrder')], 
        ];
    }

    public function resolve($root, $args)
    {

        $qb = Article::orderBy('id', 'desc');

        if (isset($args['order'])) {
            if ($args['order'] == 'COMMENTED') {
                $qb = Article::orderBy('updated_at', 'desc'); //TODO:: later update article->commented while commented ...
            } else if ($args['order'] == 'HOT') {
                $qb = Article::orderBy('hits', 'desc');
            }
        }

        if (isset($args['filter'])) {
            switch ($args['filter']) {
                case 'DRAFTS':
                    $qb = $qb->where('status', '=', 0);
                    break;

                case 'DELETED':
                    $qb = $qb->where('status', '<=', 0);
                    break;

                default:
                    $qb = $qb->where('status', '>', 0);
                    break;
            }
        }

        if (isset($args['hot_time'])) {
            $qb = $qb->orderBy('hits', 'desc');
            $qb = $qb->where('updated_at', '>', \Carbon\Carbon::now()->addDays(-$args['hot_time']));
        }

        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }

        if (isset($args['category_id'])) {
            $qb = $qb->where('category_id', $args['category_id']);
        }

        if (isset($args['collection_id'])) {
            $collection = \App\Collection::findOrFail($args['collection_id']);
            $user       = session('user');
            if ($user && $collection->user_id == $user->id) {
                $qb = $collection->allArticles();  
            } else {
                $qb = $collection->articles();
            }
        }

        if (isset($args['filter'])) {
            if ($args['filter'] == 'TOP') {
                $qb = $qb->where('is_top', 1)->where('image_top', '<>', '');
            }
        }

        if (isset($args['filter']) && $args['filter'] == 'LIKED') {
            if (!isset($args['user_id'])) {
                throw new Exception('查看用户收藏的文章必须提供user_id');
            }
            $user = \App\User::findOrFail($args['user_id']);
            $qb   = $user->likes()->where('liked_type', 'articles');
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);

        if (isset($args['filter']) && $args['filter'] == 'LIKED') {
            $articles = [];
            foreach ($qb->get() as $like) {
                $articles[] = $like->liked;
            }
            return $articles;
        }

        $articles = $qb->get();
        return $articles;
    }
}
