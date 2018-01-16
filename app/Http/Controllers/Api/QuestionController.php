<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function search_question(Request $request)
    {
        // search question %
        $query     = $request->get('que');
        $questions = Question::where('title', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->take(10)
            ->pluck('title','id');


        return $questions;
    }

    public function search_question_image(Request $request)
    {
        $query  = $request->get('search');
        $images = Image::where('title', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->take(10)
            ->pluck('path_small','id');

        return $images;
    }
}
