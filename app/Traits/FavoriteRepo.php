<?php

namespace App\Traits;

use App\Favorite;
use App\User;
use Symfony\Component\VarDumper\Cloner\Data;

trait FavoriteRepo
{
    public function toggleFavorite($rootValue, array $args, $context, $resolveInfo)
    {
        //只能简单创建
        $user = getUser();
        $favorite = Favorite::firstOrNew([
            'user_id'    => $user->id,
            'faved_id'   => $args['article_id'],
             'faved_type' => 'articles'
            ]);
        //取消收藏
        if ($favorite->id) {
            $favorite->delete();
        } else {
            $favorite->save();
        }
        
        return $favorite;
    }
}
