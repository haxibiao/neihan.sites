<?php

namespace App\Traits;

use App\Article;
use App\Like;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait LikeResolvers
{
    public function getByType($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user('获取喜欢列表');

        return Like::where('liked_type', $args['liked_type']);
    }

    public function create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        unset($args['directive']);
        return Like::firstOrCreate($args);
    }

    public function toggleTheLike($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user('喜欢操作');

        //只能简单创建
        $user = getUser();
        $like = Like::firstOrNew([
            'user_id' => $user->id,
            'liked_id' => $args['liked_id'],
            'liked_type' => $args['liked_type'],
        ]);

        //取消喜欢
        if (($args['undo'] ?? false) || $like->id) {
            $like->delete();
            $like->isLiked = false;
        } else {
            app_track_user("点赞", 'like_' . $like->likable_type, $like->likable_id);
            $like->save();
            $like->isLiked = true;
        }
        //更新关联模型数据
        $like->likable->count_likes = $like->likable->likes()->count();

        $like->likable->save();
        return $like;
    }

    public function resolveLikeArticle($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $liked_type = $args['liked_type'];
        $user = User::find($args['user_id']);

        $like_builder = $user->likes();

        $like_articles_id = $user->likedArticles()->pluck('liked_id');
        $articles_id = Article::whereIn('id', $like_articles_id)->whereNotNull('video_id')->pluck('id');

        if ($liked_type == 'videos') {
            return $like_builder->where('liked_type', "articles")->whereIn('liked_id', $articles_id)->groupBy("liked_id")->orderBy('id', 'desc');
        } else {
            return $like_builder->where('liked_type', $liked_type)->groupBy("liked_id")->orderBy('id', 'desc');
        }
    }
}
