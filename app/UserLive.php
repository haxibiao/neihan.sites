<?php

namespace App;

use App\Traits\UserLive\UserLiveRepo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLive extends Model
{

    protected $guarded = [];

    protected $table = 'user_lives';

    use UserLiveRepo;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function live(): BelongsTo
    {
        return $this->belongsTo(LiveRoom::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
