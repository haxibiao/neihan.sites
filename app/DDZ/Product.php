<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{

    protected $connection = 'dongdezhuan';

    public const STATUS_ONLINE = 1;
    public const STATUS_OFFLINE = 2;

    public const PAYMENT_DIAMOND = 'diamond';
    /**
     * 限量抢
     */
    public const TYPE_CARD  = 'highWithdrawCards';
    /**
     * 高额提现令牌
     */
    public const TYPE_BADGE = 'highWithdrawBadges';

    protected $fillable = [
        'name',
        'details',
        'picture',
        'currency',
        'price',
        'status',
        'type',
        'rate'
    ];

    public function scopeOnline($query)
    {
        return $query->where('status',self::STATUS_ONLINE);
    }
}
