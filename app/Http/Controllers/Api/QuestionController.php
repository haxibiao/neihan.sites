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
        $data      = [];
        $query     = $request->get('que');
        $questions = Question::where('title', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        if ($questions->isEmpty()) {
            foreach ($questions as $question) {
                $data['title'][] = $question->title;
            }
        }
        return $data['title'];
    }

    public function search_question_image(Request $request)
    {
        $query  = $request->get('search');
        $images = Image::where('title', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        if ($questions->isEmpty()) {
            foreach ($questions as $question) {
                $data['image_url'][] = $image->path_small;
            }
        }

        return $data['image_url'];
    }
}
