<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    protected $fillable = [
        'name',
        'author',
        'start_at',
        'dead_at',
    ];

    public function teams()
    {
        return $this->hasMany(\App\Team::class);
    }

    public function match(){
        return $this->hasMany(\App\Match::class);
    }
}
