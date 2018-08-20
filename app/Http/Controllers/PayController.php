<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tip;
use Auth;

class PayController extends Controller
{
    public function pay()
    {
        $amount  = request('amount');
        $message = urldecode(request('message'));
        if (Auth::check() && Auth::user()->balance() > $amount) {
            if (request('article_id')) {
                $user    = getUser();
                $article = \App\Article::findOrFail(request('article_id'));
                $tip     = $article->tip($amount, $message);
                $postType = '文章';
                if($article->type == 'video'){
                    $postType ='视频';
                }
                $log_mine   = '向' . $article->user->link() . '的'.$postType . $article->link() . '打赏' . $amount . '元';
                $log_theirs = $user->link() . '向您的'.$postType . $article->link() . '打赏' . $amount . '元';
                $user->transfer($amount, $article->user, $log_mine, $log_theirs, $tip->id);

            }
            return redirect()->to('/wallet');
        } else {
            //未登录或者不够钱的直接支付
            $realPayUrl = '/alipay/wap/pay?amount=' . $amount . '&type=' . request('type');
            if (request('article_id')) {
                $realPayUrl .= '&article_id=' . request('article_id');
            }
            //赞赏留言传过去
            if (request('message')) {
                session(['last_tip_message' => request('message')]);
            }
            return redirect()->to($realPayUrl);
        }
    }

}
