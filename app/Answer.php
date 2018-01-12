<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $touches = ['question'];

    public function question()
    {
        return $this->belongsTo(\App\Question::class);
    }
}
