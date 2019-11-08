<?php

namespace App\Traits;

use App\Follow;
use GraphQL\Type\Definition\ResolveInfo;
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
}
