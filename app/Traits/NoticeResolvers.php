<?php

namespace App\Traits;

use App\Notice;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait NoticeResolvers
{
    /**
     * 发送验证码
     */
    public function resolveNotice($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $noticbuild = Notice::where('expires_at', '>', now());
        return $noticbuild;
    }

}
