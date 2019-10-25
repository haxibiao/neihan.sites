<?php

namespace App\Http\GraphQL\Mutations;


use App\Follow;

class FollowMutators
{
    public function toggleFollow($root, array $args, $context)
    {
        //只能简单创建
        $user = getUser();
        $follow = Follow::firstOrNew([
            'user_id'    => $user->id,
            'followed_id'   => $args['followed_id'],
            'followed_type' => $args['followed_type'],
        ]);
        //取消喜欢
        if (($args['undo'] ?? false) || $follow->id) {
            $follow->delete();
            $follow->isFollowed = false;
        } else {
            $follow->save();
            $follow->isFollowed = true;
        }
        return $follow;
    }
}