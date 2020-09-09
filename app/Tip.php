<?php

namespace App;

use App\Traits\TipResolvers;

class Tip extends Model
{
    use TipResolvers;

    public $fillable = [
        'user_id',
        'tipable_id',
        'tipable_type',
        'amount',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function tipable()
    {
        return $this->morphTo();
    }

    //actionable target, 动态 - 打赏了 - 文章
    public function target()
    {
        return $this->morphTo();
    }
}
