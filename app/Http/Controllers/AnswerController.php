<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Notifications\QuestionAnswered;
use App\Image;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user   = $request->user();
        $answer = new Answer($request->all());
        //从文章地址(https://l.dongmeiwei.com/article/1139)提取文章id （1139）
        if (starts_with($answer->article_id, 'http')) {
            $answer->article_id = parse_url($answer->article_id, PHP_URL_PATH);
            $answer->article_id = str_replace('/article/', '', $answer->article_id);
        }
        $answer->save();

        //get this answer request category
        $question =$answer->question;
        if($answer->article){
            $categories =$answer->article->categories;
            $category_ids =$categories->pluck('id');
            $answer->answer =$answer->article->body;
            $question->categories()->syncWithoutDetaching($category_ids);
            foreach($categories as $category){
                $category->count_questions= $category->questions->count();
                $category->save();
            }
        }

        //find answer content image

        $imgs=extractImagePaths($answer->answer);

        if(!empty($imgs)){
            $answer->image_url=$imgs[0];
            $image =Image::where('path',$answer->image_url)->first();
            if($image){
                $answer->image_url= $image->path_small();
            }
        }

        //no images, use article image
        if (empty($answer->image_url) && $answer->article) {
            $answer->image_url = $answer->article->image_url;
        }
        $answer->save();

        //question user notify
        $question->user->notify(new QuestionAnswered($user->id, $question->id));
        
        //save question relations;
        $this->save_question_relation($answer);

        if (!$question->latest_answer_id) {
            $question->latest_answer_id = $question->answers()->first()->id;
        }
        $question->save();

        return redirect()->to('/question/'.$request->question_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function save_question_relation($answer)
    {
        $question=$answer->question;
        $question->count_answers++;
        $question->save();
    }
}
