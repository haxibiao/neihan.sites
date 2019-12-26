<?php

namespace App\Dongdezhuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $connection = 'dongdezhuan';

    protected  $fillable = [
        'user_id',
        'app_id',
        'app_user_id',
        'type',
        'amount',
        'remark',
        'status',
        'receipt'
    ];

    public const STATUS_FAILED = -1;
    public const STATUS_PROCESSING = 0;
    public const STATUS_SUCCESS = 1;

    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\User::class);
    }


    public function app():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\App::class);
    }

    public static function getStatus():array
    {
        return [
            self::STATUS_FAILED     => '处理失败',
            self::STATUS_PROCESSING => '处理中',
            self::STATUS_SUCCESS    => '处理成功',
        ];
    }
}
