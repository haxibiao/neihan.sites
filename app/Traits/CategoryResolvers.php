<?php

namespace App\Traits;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait CategoryResolvers
{

    public function resolveRequestedCategories($root, array $args, $context)
    {
        //TODO: 应该是要实现正在投稿的专题列表
        return [];
    }
    // resolvers
    public function getByType($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $category = self::where("type", $args['type'])->where("status", 1)->orderBy("order", "desc");
        return $category;
    }

    // resolvers
    public function resolveAdmins($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $category = self::findOrFail($args['category_id']);
        return $category->admins();
    }

    public function resolveAuthors($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $category = self::findOrFail($args['category_id']);
        return $category->authors();
    }

    public function resolveCategories($root, array $args, $context)
    {
        app_track_event('首页', '获取专题列表');

        $filter = $args['filter'] ?? 'hot';
        //TODO 紧急兼容其它站点老数据问题
        //$qb     = \App\Category::whereIn('type', ['video','article']); //视频时代，避开图文老分类
        $qb = \App\Category::whereStatus(1); //需上架

        //确保是近1个月内更新过的专题（旧的老分类适合图文时代，可能很久没人更新内容进入了）
        // $qb = $qb->where('updated_at', '>', now()->addMonth(-1));

        //热门话题
        if ($filter == 'hot') {
            $qb = $qb->orderBy('count', 'desc');
        } else {
            //最新话题
            $qb = $qb->orderBy('id', 'desc');
        }

        return $qb;
    }
}
