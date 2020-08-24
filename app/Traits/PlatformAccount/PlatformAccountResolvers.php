<?php

namespace App\Traits;

use App\PlatformAccount;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait PlatformAccountResolvers
{
    //获取规格
    public function getDimension($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $type = $args['type'];
        $product_id = $args['product_id'];

        return PlatformAccount::getDimensionByType($type, $product_id);
    }

    //根据Dimension获取价格
    public function getPrice($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $dimension = $args['dimension'];
        $dimension2 = $args['dimension2'];
        $product_id = $args['product_id'];

        return PlatformAccount::getPriceByDimension($product_id, $dimension, $dimension2);
    }
}
