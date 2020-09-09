<?php

namespace App\Traits;

trait FavoriteResolvers
{
    public function getByType($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Follow::where('faved_type', $args['faved_type']);
    }
}
