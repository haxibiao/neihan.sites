<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'compare_id',
        'round',
        'type',
        'score',
        'winner',
        'TA',
        'TB',
        'start_at',
    ];

    public function compare()
    {
        return $this->belongsTo(\App\Compare::class);
    }

    public function TA()
    {
        return $this->belongsTo(\App\User::class, 'TA');
    }

    public function TB()
    {
        return $this->belongsTo(\App\User::class, 'TB');
    }
}
