<?php

namespace App\Traits;

use App\Article;
use App\Like;

trait LikeRepo
{
    public function toggleLike($input)
    {
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
        $like_obj = $like->liked;
        if ($input['likable_type'] == 'comments') {
            $like_obj->liked = $liked_flag;
        }
        return $like_obj;
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
            $article       = Article::findOrFail($input['likable_id']);
            $data['likes'] = $article->likes()
                ->with(['user' => function ($query) {
                    $query->select('id', 'name', 'avatar');
                }])->paginate(10);
        }
        return $data;
    }
}
