<?php

namespace App;

use App\PlatformAccount;
use App\Product;
use App\Traits\OrderAttrs;
use App\Traits\OrderRepo;
use App\Traits\OrderResolvers;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use OrderResolvers;
    use OrderAttrs;
    use OrderRepo;

    protected $fillable = [
        'user_id',
        'number',
        'status',
    ];

    const UNPAY = 0; //未支付
    const PAID = 1; //已支付
    const RECEIVED = 2; //已到货
    const EXPIRE = 3; //已过期
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function products()
    {
        return $this->belongsToMany(\App\Product::class, "product_order")
            ->withPivot("amount")
            ->withPivot("price")
            ->withTimestamps();
    }

    public function platformAccount()
    {
        return $this->hasMany(\App\PlatformAccount::class);
    }
}