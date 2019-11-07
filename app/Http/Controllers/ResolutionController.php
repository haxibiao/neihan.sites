<?php

namespace App\Http\Controllers;

use App\Image;
use App\Notifications\QuestionAnswered;
use App\Resolution;
use Illuminate\Http\Request;

class ResolutionController extends Controller
{


    public function store(Request $request)
    {
        $user   = $request->user();
        $resolution = new Resolution($request->all());
        if(empty($resolution->answer)) {
            if(checkEditor()){
                $resolution->answer = '<p></p>'; //允许编辑用户填写文章后，答案不写
            } else {
                dd('回答不能是空白的!');
            }
        }
        $js_reg='#<a.*?href="(.*?)".*?#';
        preg_match($js_reg, $resolution->answer,$answerText);
        if($answerText)
        {
            dd('提交含有非法字符，请重新回答');
        }
        //从文章地址(https://dongmeiwei.com/article/1139)提取文章id （1139）
        if (starts_with($resolution->article_id, 'http')) {
            $resolution->article_id = parse_url($resolution->article_id, PHP_URL_PATH);
            $resolution->article_id = str_replace('/article/', '', $resolution->article_id);
        }
        $resolution->save();

        $issue = $resolution->issue;
        //根据回答的文章的分类关系，定义问题的分类关系
        if ($resolution->article) {
            $categories   = $resolution->article->categories;
            $category_ids = $categories->pluck('id');
            $issue->categories()->syncWithoutDetaching($category_ids);
            foreach ($categories as $category) {
                $category->count_questions = $category->issues->count();
                $category->save();
            }
        }

        //find images
        $imgs = extractImagePaths($resolution->answer);
        if (!empty($imgs)) {
            $resolution->image_url = $imgs[0];
            $image = Image::where('path', $resolution->image_url)->first();
            if($image) {
                $resolution->image_url = $image->path_small();
            }
        }

        //no images, use article image
        if (empty($resolution->image_url) && $resolution->article) {
            $resolution->image_url = $resolution->article->image_url;
        }
        $resolution->save();

        //消息提醒
        $issue->user->notify(new QuestionAnswered($user->id, $issue->id));
        //刷新消息数字
        $issue->user->forgetUnreads();

        //update question counts
        $issue                = $resolution->issue;
        $issue->count_answers = $issue->resolutions()->count();

        //latest answer
        if (!$issue->latest_resolution_id) {
            $issue->latest_resolution_id = $issue->resolutions()->first()->id;
        }
        $issue->save();

        return redirect()->to('/question/' . $resolution->issue_id);
    }
}
