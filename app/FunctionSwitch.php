<?php

namespace App;

use App\Exceptions\GQLException;
use Illuminate\Database\Eloquent\Model;

class FunctionSwitch extends Model
{
    protected $table    = 'function_switchs';
    protected $fillable = [
        'name',
        'state',
        'close_details',
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

    public static function close_function($name)
    {

        $item  = \App\FunctionSwitch::where('name', $name)->first();
        $state = $item ? $item->state : 1;
        if (!$state) {
            throw new GQLException($item->close_details);
        }
    }
}
