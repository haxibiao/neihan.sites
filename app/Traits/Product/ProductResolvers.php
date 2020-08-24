<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait ProductResolvers
{
    //根据商店id查询商品
    // public function getProducts($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
    //     if($user = checkUser()){

    //         return Product::where('store_id',$args['store_id'])->whereIn("status",[0,1]);
    //     }else{
    //         throw new GQLException("客户端没有登录。。。");
    //     }
    // }

    //根据分类id查询商品
    public function getProductsByCategory($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        if ($user = checkUser()) {

            $qb = Product::with("image")
                ->where('category_id', $args['category_id'])
                ->where("status", 1);

            return $qb;
        } else {
            throw new GQLException("客户端没有登录。。。");
        }
    }
}
