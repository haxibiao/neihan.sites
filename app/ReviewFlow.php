<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewFlow extends Model
{
    protected $fillable = [
        'name',
        'check_functions',
        'need_owner_review',
        'need_offical_review',
        'type',
    ];

    protected $casts = [
        'check_functions' => 'array',
    ];

    //任务类型
    const ADMIN_USER_SCOPE  = 1; //运营选用
    const NORMAL_USER_SCOPE = 2; //用户可选用

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function getTypes()
    {
        return [
            self::ADMIN_USER_SCOPE  => '运营选用',
            self::NORMAL_USER_SCOPE => '用户可选用',
        ];
    }
}
