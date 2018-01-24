<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionInvite extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'invite_user_id',
    ];
}
