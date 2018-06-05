<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{ 
    protected $fillable = [
        'user_id',
        'visited_id', 
        'visited_type',
    ];
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function visited() 
    {
        return $this->morphTo();
    }
}
