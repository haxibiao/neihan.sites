<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    protected $fillable = [
        'name',
        'author',
        'count',
        'start_at',
        'dead_at',
        'description',
    ];

    public function teams()
    {
        return $this->hasMany(\App\Team::class);
    }

    public function matchs()
    {
        return $this->hasMany(\App\Match::class);
    }

    public function match_score()
    {
          
    }

}
