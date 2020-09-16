<?php


namespace App\Traits;


use App\Exceptions\GQLException;
use App\Exceptions\UserException;
use App\User;

trait NotLikeRepo
{
    /**
     * 屏蔽用户
     *
     * @param int $id
     * @param string $type
     * @param User $user
     * @return mixed
     * @throws \Throwable
     */
    public static function store(int $id, string $type, User $user)
    {
        //该记录是否存在
        $notLiked = static::firstOrNew([
            'not_likable_id'   => $id,
            'not_likable_type' => $type,
            'user_id'          => $user->id,
        ]);
        throw_if(isset($notLiked->id), GQLException::class, '屏蔽失败,您用户已屏蔽过了!');
        $notLiked->save();
        return $notLiked;
    }

}