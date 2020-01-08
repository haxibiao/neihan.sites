<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class App extends Model
{

    protected $connection = 'dongdezhuan';

    const STATUS_OFFLINE = -1;
    const STATUS_ONLINE  = 0;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'description',
        'rank',
        'status',
        'count_comments',
        'count_hits',
        'count_follows',
        'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    public function scopeOnline($query)
    {
        return $query->where('status', self::STATUS_ONLINE);
    }
}
