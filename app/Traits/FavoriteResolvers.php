<?php

namespace App\Traits;

trait FavoriteResolvers
{
    public function getByType($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user('获取收藏列表');
        return Follow::where('faved_type', $args['faved_type']);
    }
}
