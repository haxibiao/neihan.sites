<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'last_watch_time',
        'progress',
        'series_id'
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
