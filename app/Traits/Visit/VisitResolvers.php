<?php

namespace App\Traits;

use App\Article;
use App\User;
use App\Visit;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait VisitResolvers
{
    public function getVisits($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event('用户页', '获取浏览记录');

        $articles = Article::whereNull('video_id')->pluck('id');
        $user     = User::find($args['user_id']);
        return Visit::where('user_id', $args['user_id'])->whereIn('visited_type', ['articles'])->whereNotIn(
            'visited_id',
            $articles
        )->latest('id');
    }

    public function getByDate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event('用户页', '获取浏览记录');

        if (isset($args['filter'])) {
            if ($args['filter'] == 'TODAY') {
                return Visit::where('created_at', '>=', today());
            } else if ($args['filter'] == 'EARLY') {
                return Visit::where('created_at', '<', today());
            }
        }
    }

    public function resolvecreatevisit($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Visit::firstOrCreate([
            'user_id'      => $args['user_id'],
            'visited_id'   => $args['visited_id'],
            'visited_type' => $args['visited_type'],
        ]);
    }
}
