<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Image;
use App\Notifications\QuestionAnswered;
use Illuminate\Http\Request;

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
        if(empty($answer->answer)) {
            if(checkEditor()){
                $answer->answer = '<p></p>'; //允许编辑用户填写文章后，答案不写
            } else {
                dd('回答不能是空白的!');
            }
        }
        $js_reg='#<a.*?href="(.*?)".*?#';
        preg_match($js_reg, $answer->answer,$answerText);
        if($answerText)
        {
            dd('提交含有非法字符，请重新回答');
        }
        //从文章地址(https://dongmeiwei.com/article/1139)提取文章id （1139）
        if (starts_with($answer->article_id, 'http')) {
            $answer->article_id = parse_url($answer->article_id, PHP_URL_PATH);
            $answer->article_id = str_replace('/article/', '', $answer->article_id);
        }
        $answer->save();

        $question = $answer->question;
        //根据回答的文章的分类关系，定义问题的分类关系
        if ($answer->article) {
            $categories   = $answer->article->categories;
            $category_ids = $categories->pluck('id');
            $question->categories()->syncWithoutDetaching($category_ids);
            foreach ($categories as $category) {
                $category->count_questions = $category->questions->count();
                $category->save();
            }
        }

        //find images
        $imgs = extractImagePaths($answer->answer);
        if (!empty($imgs)) {
            $answer->image_url = $imgs[0];
            $image = Image::where('path', $answer->image_url)->first();
            if($image) {
                $answer->image_url = $image->path_small();
            }
        }

        //no images, use article image
        if (empty($answer->image_url) && $answer->article) {
            $answer->image_url = $answer->article->image_url;
        }
        $answer->save();

        //消息提醒
        $question->user->notify(new QuestionAnswered($user->id, $question->id));
        //刷新消息数字
        $question->user->forgetUnreads();

        //update question counts
        $question                = $answer->question;
        $question->count_answers = $question->answers()->count();

        //latest answer
        if (!$question->latest_answer_id) {
            $question->latest_answer_id = $question->answers()->first()->id;
        }
        $question->save();

        return redirect()->to('/question/' . $answer->question_id);
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
}
