<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $fillable = [
        'group', 'state', 'name',
    ];

    // 开启
    const STATUS_ON = 1;

    // 关闭
    const STATUS_OFF = 0;

    public function getStatusAttribute()
    {
        switch ($this->state) {
            case self::STATUS_ON:
                return 'on';
            case self::STATUS_OFF:
                return 'off';
            default:
                return "on";
        }
    }
}
