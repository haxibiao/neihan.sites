<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuth extends Model
{
    use Traits\OAuthResolvers,
        Traits\OAuthRepo;

    protected $fillable = [
        'user_id',
        'oauth_id',
        'oauth_type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
