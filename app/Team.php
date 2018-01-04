<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'compare_id',
        'type',
        'team_score',
        'out',
        'description',
        'history',
        'status',
        'name',
        'group',
    ];

    public function compare()
    {
        return $this->belongsTo(\App\Compare::class);
    }

    public function user()
    {
        return $this->belongsToMany(\App\User::class);
    }

    public function match_history()
    {
        return $this->history;
    }

    //computed
}
