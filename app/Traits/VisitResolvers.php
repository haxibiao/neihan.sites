<?php

namespace App\Traits;

use App\Visit;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait VisitResolvers
{
    public function getVisits($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Visit::latest('id');
    }

    public function getByDate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (isset($args['filter'])) {
            if ($args['filter'] == 'TODAY') {
                return Visit::where('created_at', '>=', today());
            } else if ($args['filter'] == 'EARLY') {
                return Visit::where('created_at', '<', today());
            }
        }
    }

    public function create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Visit::firstOrCreate([
            'user_id'      => $args['user_id'],
            'visited_id'   => $args['visited_id'],
            'visited_type' => $args['visited_type'],
        ]);
    }
}
