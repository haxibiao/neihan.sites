<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    //

    protected $fillable = [
        'user_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
