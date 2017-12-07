<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'qq',
        'introduction',
        'is_editor',
        'is_seoer',
        'seo_meta',
        'seo_push',
        'seo_tj',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function followings()
    {
        return $this->hasMany(\App\Follow::class);
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function messages()
    {
        return $this->hasMany(\App\Message::class);
    }

    public function actions()
    {
        return $this->hasMany(\App\Action::class);
    }

    public function collections()
    {
        return $this->hasMany(\App\Collection::class);
    }

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    public function collection()
    {
        return $this->hasMany(\App\Collection::class);
    }

    public function chats()
    {
        return $this->belongsToMany(\App\Chat::class)->withPivot('with_users');
    }

    public function avatar(){
    	return get_avatar($this);
    }

}
