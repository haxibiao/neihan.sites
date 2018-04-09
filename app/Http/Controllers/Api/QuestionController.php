<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Image;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QuestionController extends Controller
{
    public function search_question(Request $request)
    {
        // search question %
        $query     = $request->get('que');
        $questions = Question::where('title', 'like', '%' . $query . '%')
            ->take(10)
            ->get();

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
        $user     = $request->user();
        $cacheKey = $user->id . 'answer' . $id;
        $answer   = Answer::findOrFail($id);
        if (!cache::get($cacheKey) || $user->is_editor) {
            $answer->count_reports++;
            $answer->save();
            $answer->reported = 1;

            cache::put($cacheKey, 1, 24 * 60);
        }

        return $answer;
    }

    public function QuestionReport(Request $request, $id)
    {
        $user     = $request->user();
        $cacheKey = $cacheKey = $user->id . 'question' . $id;
        $question = Question::findOrFail($id);
        if (!cache::get($cacheKey) || $user->is_editor) {
            $question->count_reports++;
            $question->save();
            $question->reported = 1;

            cache::put($cacheKey, 1, 24 * 60);
        }

        return $question;
    }

    public function get(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        return $question;
    }

    public function commend(Request $request)
    {
    	$questions=Question::orderBy('hits','desc')->where('image1','<>',null)->take(3)->get();

    	return $questions;
    }
}
