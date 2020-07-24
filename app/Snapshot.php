<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    protected $fillable = [
        'user_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
