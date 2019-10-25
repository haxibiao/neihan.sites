<?php

namespace App;

use App\Article;
use App\Model;
use App\Traits\LikeResolvers;

class Like extends Model
{
    use LikeResolvers;
    protected $fillable = [
        'user_id',
        'liked_id',
        'liked_type',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function liked()
    {
        return $this->morphTo();
    }

    //兼容likable的写法
    public function likable()
    {
        return $this->morphTo('liked');
    }

    public function article()
    {
        return $this->belongsTo(\App\Article::class, 'liked_id');
    }

    public function comment()
    {
        return $this->belongsTo(\App\Comment::class, 'liked_id');
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- repo ----------------------------- */
    /* --------------------------------------------------------------------- */

    // TODO: move out
    public function toggleLike($input)
    {
        //只能简单创建
        $user = getUser();
        $like = Like::firstOrNew([
            'user_id' => $user->id,
            'liked_id' => $input['liked_id'],
            'liked_type' => $input['liked_type'],
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
        if ($input['liked_type'] == 'comments') {
            $like_obj->liked = $liked_flag;
        }
        return $like_obj;
    }
    /**
     * @Desc     获取喜欢的用户
     * @DateTime 2018-07-24
     * @return   [type]     [description]
     */
    public function likeUsers($input)
    {
        if (checkUser()) {
            $user = getUser();
            $input['user_id'] = $user->id;
            $like = Like::firstOrNew($input);
            $data['is_liked'] = $like->id;
        }
        $data['likes'] = [];
        if ($input['liked_type'] == 'articles') {
            $article = Article::findOrFail($input['liked_id']);
            $data['likes'] = $article->likes()
                ->with(['user' => function ($query) {
                    $query->select('id', 'name', 'avatar');
                }])->paginate(10);
        }
        return $data;
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('liked_type', $type);
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
