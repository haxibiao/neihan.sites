<?php

namespace App;

use App\Model;

class QuestionInvite extends Model
{
    public $fillable = [
    	'user_id',
    	'question_id',
    	'invite_user_id',
    ];
}
