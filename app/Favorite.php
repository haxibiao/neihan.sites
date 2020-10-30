<?php

namespace App;

use App\Model;
use App\Traits\FavoriteRepo;
class Favorite extends Model
{
    use FavoriteRepo;
    
    protected $fillable = [
        'user_id',
        'faved_id',
        'faved_type',
    ];


    public function faved()
    {
        return $this->morphTo();
    }

    public function article()
    {
        return $this->belongsTo(\App\Article::class, 'faved_id');
    }


    public function user()
    {
        $this->belongsTo(\App\User::class);
    }

    //actionable target, 比如 活动记录 - 收藏了 - 对象(文章，用户等)
    public function target()
    {
        return $this->morphTo();
    }
    
    public function getFavoritedAttribute()
    {
        if ($user = getUser(false)) {
            return $favorite = $user->favoritedArticles()->where('faved_id', $this->article->id)->count() > 0;
        }
        return false;
    }
}
