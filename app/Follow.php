<?php

namespace App;

use App\Model;
use App\Traits\FollowAttrs;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    use FollowAttrs;

    public $fillable = [
        'user_id',
        'followed_type',
        'followed_id',
    ];

    public function followed()
    {
        return $this->morphTo();
    }

    public function people(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'followed_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Category::class, 'followed_id');
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(\App\Collection::class, 'followed_id');
    }
}
