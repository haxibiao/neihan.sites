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
            'user_id'       => [
                'name' => 'user_id', 
                'type'         => Type::int()
            ],
            'category_id'   => [
                'name' => 'category_id', 
                'type'     => Type::int()
            ],
            'collection_id' => [
                'name' => 'collection_id', 
                'type'   => Type::int()
            ],
            'limit'         => [
                'name' => 'limit', 
                'type'   => Type::int()
            ],
            'offset'        => [
                'name' => 'offset', 
                'type'  => Type::int()
            ], 
            'filter'        => [
                'name' => 'filter', 
                'type'  => GraphQL::type('ArticleFilter')
            ],
            'in_days'       => [
                'name' => 'in_days', 
                'type' => Type::int()
            ],
            'order'         => [
                'name' => 'order',  
                'type'  => GraphQL::type('ArticleOrder')
            ],
            'type'          => [
                'name' => 'type', 
                'type'    => GraphQL::type('ArticleType')
            ],
            'keyword'     => [
                'name' => 'keyword'   , 
                'type'    => Type::string()
            ],
        ];
    } 

    public function resolve($root, $args)
    {

        $qb = Article::whereNull('source_url');
        //下面代码注释掉的原因避免，用户发布一篇新文章在手机duan自己主页的公开文章中查询不到
        /*->where('category_id', '>', 0)*/;
        if (isset($args['keyword'])) {
            $keyword = trim($args['keyword']);
            if( empty( $keyword ) ){
                return null;
            } 
            $qb = $qb->where('status', 1)
                ->where(function($query) use($keyword){
                    $query->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('keywords', 'like', '%' . $keyword . '%');
                  });
            $results = $qb->count();
            //记录用户搜索日志
            if ( $results ) { 
                //保存全局搜索
                $query_item = \App\Query::firstOrNew([
                    'query' => $keyword,
                ]);
                $query_item->results = $results;
                $query_item->hits++;
                $query_item->save();

                //保存个人搜索,游客也进行记录
                $query_log = \App\Querylog::firstOrNew([
                    'user_id' => checkUser() ? getUser()->id : null,
                    'query'   => $keyword,
                ]);
                $query_log->save();
            }
        }
        //排序
        if (isset($args['order'])) {
            if ($args['order'] == 'COMMENTED') {
                $qb = $qb->orderBy('updated_at', 'desc'); //TODO:: later update article->commented while commented ...
            } else if ($args['order'] == 'HOT') {
                $qb = $qb->orderBy('hits', 'desc');
            } else{
                $qb = $qb->orderBy('id', 'desc');
            }
        } else {
            $qb = $qb->orderBy('id', 'desc');
        }

        //TODO关于filter的代码需要重构
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
        } else {
            $qb = $qb->where('status', '>', 0);
        }
        //文章的类型，目前主要有视频与普通文章
        if (isset($args['type'])) {
            switch ($args['type']) {
                case 'VIDEO':
                    $qb = $qb->where('type','=','video');  
                    break;

                case 'ARTICLE':
                    $qb = $qb->where('type','=','article');
                    break;

                default:
                    break;
            }
        }
        

        if (isset($args['in_days'])) {
            $qb = $qb->where('created_at', '>', \Carbon\Carbon::now()->addDays(-$args['in_days']));
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
                $qb = $collection->hasManyArticles();
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
                throw new \Exception('查看用户收藏的文章必须提供user_id');
            }
            $user = \App\User::findOrFail($args['user_id']);
            $qb   = $user->likes()->where('liked_type', 'articles');
        }

        if ( isset($args['filter']) && $args['filter'] == 'RECOMMEND') {
            if( !isset($args['user_id']) ){
                return null;
            }
            $user = \App\User::findOrFail($args['user_id']);
            $following_user_ids = $user->followingUsers()
                ->pluck('followed_id');
            if(empty($following_user_ids)){
                return null;
            }
            $qb = Article::orderBy('id', 'desc')
                ->whereIn('user_id',$following_user_ids);
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
