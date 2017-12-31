<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillalbe = [
        'compare_id',
        'type',
        'team_score',
        'out',
        'description',
        'history',
        'status',
    ];

    public function compare()
    {
        return $this->belongsTo(\App\Compare::class);
    }
}
