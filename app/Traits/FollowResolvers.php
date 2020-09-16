<?php

namespace App\Traits;

use App\Follow;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait FollowResolvers
{
    public function getByType($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Follow::where('followed_type', $args['followed_type']);
    }

    public function createFollow($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        unset($args['directive']);
        return Follow::firstOrCreate($args);
    }

    public function toggleFollow($root, array $args, $context)
    {
        //只能简单创建
        $user = getUser();
        $followedId     = data_get($args,'followed_id');
        $followedType   = data_get($args,'followed_type');

        $modelString = Relation::getMorphedModel($followedType);
        $model = $modelString::findOrFail($followedId);
        return $user->toggleFollow($model);
    }
}
