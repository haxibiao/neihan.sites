<?php

namespace App\Http\Controllers;

use App\Article;
use App\Notifications\ArticleTiped;
use App\Tip;
use App\Transaction;
use Auth;
use DB;

class PayController extends Controller
{
    public function pay()
    {
        $amount  = request('amount');
        $message = urldecode(request('message'));
        if (Auth::check() && Auth::user()->balance() > $amount) {
            if (request('article_id')) {
                DB::transaction(function () {
                    $type    = '打赏';
                    $amount  = request('amount');
                    $article = Article::with('user')->find(request('article_id'));
                    if ($article) {
                        $log = '向' . $article->user->link() . '的文章' . $article->link() . '打赏' . $amount . '元';
                        Transaction::create([
                            'user_id' => Auth::user()->id,
                            'type'    => $type,
                            'log'     => $log,
                            'amount'  => $amount,
                            'status'  => '已到账',
                            'balance' => Auth::user()->balance() - $amount,
                        ]);

                        $log2 = Auth::user()->link() . '向您的文章' . $article->link() . '打赏' . $amount . '元';
                        Transaction::create([
                            'user_id' => $article->user->id,
                            'type'    => $type,
                            'log'     => $log2,
                            'amount'  => $amount,
                            'status'  => '已到账',
                            'balance' => $article->user->balance() + $amount,
                        ]);
                    }
                }, 3);

                $this->tip(request('article_id'), $amount, $message);
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

    public function tip($article_id, $amount, $message = '')
    {
        $article = Article::with('user')->findOrFail($article_id);
        //保存赞赏记录
        $data = [
            'user_id'      => Auth::user()->id,
            'tipable_id'   => $article->id,
            'tipable_type' => 'articles',
            'amount'       => $amount,
            'message'      => $message,
        ];
        $tip = Tip::create($data);

        //action
        $action = \App\Action::create([
            'user_id'         => Auth::id(),
            'actionable_type' => 'tips',
            'actionable_id'   => $tip->id,
        ]);

        //更新文章赞赏数
        $article->count_tips = $article->tips()->count();
        $article->save();

        //赞赏消息提醒
        $article->user->notify(new ArticleTiped($article, Auth::user(), $tip));
    }
}
