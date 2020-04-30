<?php

namespace App;

use App\Order;
use App\Product;
use App\Traits\PlatformAccountAttrs;
use App\Traits\PlatformAccountRepo;
use App\Traits\PlatformAccountResolvers;
use App\User;
use Illuminate\Database\Eloquent\Model;

class PlatformAccount extends Model
{
    //
    use PlatformAccountAttrs;
    use PlatformAccountRepo;
    use PlatformAccountResolvers;

    public $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'order_status',
        'platform',
        'dimension',
        'dimension2',
        'dimension3',
        'price',
        'account',
        'password',
    ];

    const UNUSE = 0; //未使用
    const INUSE = 1; //使用中
    const EXPIRE = 2; //已到期
    const UNUSABLE = -1; //不可用

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Product::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Order::class);
    }

}
