<?php

namespace App;

use App\Model;
use App\Traits\FollowAttrsCache;
use App\Traits\FollowResolvers;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follow extends Model
{
    use FollowAttrsCache;
    use FollowResolvers;
    use SoftDeletes;

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
