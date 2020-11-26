<?php

namespace App\Traits;

use App\Article;
use App\Like;

trait LikeRepo
{
    public function toggleLike($input)
    {
        $data = [];

        //只能简单创建
        $user = getUser();
        $like = Like::firstOrNew([
            'user_id'    => $user->id,
            'likable_id'   => $input['likable_id'],
            'likable_type' => $input['likable_type'],
        ]);
        
        //取消喜欢
        if (($input['undo'] ?? false) || $like->id) {
            $like->delete();
            $liked_flag = false;
        } else {
            $like->save();
            $liked_flag = true;
        }
         
        if ($input['likable_type'] == 'comments') {
            // 点赞状态
            $data['liked'] = $liked_flag;
            // 点赞数量
            $count_likes = Like::query()
            ->where('likable_type', 'comments')
            ->where('likable_id', $input['likable_id'])
            ->count();

            $data['count_likes'] = $count_likes;
        }
        return $data;
    }

    public function likeUsers($input)
    { 
        if (checkUser()) {
            $user             = getUser();
            $input['user_id'] = $user->id;
            $like             = Like::firstOrNew($input);
            $data['is_liked'] = $like->id;
        }

        $data['likes'] = [];
        if (isset($input['likable_type'])&&$input['likable_type'] == 'articles') {
            // 文章点赞数量
            $count_likes = Like::query()
            ->where('likable_type', 'articles')
            ->where('likable_id', $input['likable_id'])
            ->count();

            $data['likesTotal'] = $count_likes;
        }
        return $data;
    }
}
