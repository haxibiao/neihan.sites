<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Question;
use App\Image;
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
            ->pluck('title', 'id');

        return $questions;
    }

    public function search_question_image(Request $request)
    {
        $query  = $request->get('q');
        $images = Image::where('title', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return $images;
    }

    public function likeAnswer(Request $reqeust, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->count_likes++;
        $answer->save();
        $answer->liked = 1;

        return $answer;
    }

    public function unlikeAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->count_unlikes++;
        $answer->save();
        $answer->unliked = 1;

        return $answer;
    }

    public function deleteAnswer(Request $request, $id)
    {
        $answer         = Answer::findOrFail($id);
        $answer->status = -1;
        $answer->save();
        $answer->deleted = 1;

        return $answer;
    }

    public function reportAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->count_reports++;
        $answer->save();
        $answer->reported = 1;
        
        return $answer;
    }
}
