<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Controllers\Controller;

class AnswerController extends Controller
{
    public function get($id)
    {
        if ($id) {
            $answer                 = Answer::findOrFail($id);
            $answer->count_likes    = $answer->count_likes ? $answer->count_likes : 0;
            $answer->count_unlikes  = $answer->count_unlikes ? $answer->count_unlikes : 0;
            $answer->count_comments = $answer->commments()->count();
            $answer->count_reports  = $answer->count_reports ? $answer->count_reports : 0;
            return $answer;
        }
    }
}
