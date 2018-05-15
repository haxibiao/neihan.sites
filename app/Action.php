<?php

namespace App;

use App\Model;

class Action extends Model
{
    public $fillable = [
        'user_id',
        'actionable_type',
        'actionable_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function actionable()
    {
        return $this->morphTo();
    }
}
