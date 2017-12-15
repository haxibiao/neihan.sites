<?php

namespace App;

use App\Model;

class Action extends Model
{
    protected $fillable = [
        'user_id',
        'actionable_type',
        'actionable_id',
    ];

    public function actionable()
    {
        return $this->morphTo();
    }
}
