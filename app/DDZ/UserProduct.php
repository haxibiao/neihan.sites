<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProduct extends Pivot
{
    use SoftDeletes;

    protected $connection = 'dongdezhuan';

    protected $table = 'user_products';

    protected $fillable = [
        'user_id',
        'product_id',
        'expired_at',
        'scope'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(\App\DDZ\Product::class);
    }
}
