<?php

namespace App\Http\Controllers;

use App\Article;
use App\Transaction;
use Auth;
use DB;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function pay()
    {
        $amount = request('amount');

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
            }
            return redirect()->to('/wallet');
        } else {
            //未登录或者不够钱的-=
            $realPayUrl = '/alipay/wap/pay?amount=' . $amount . '&type=' . request('type');
            if (request('article_id')) {
                $realPayUrl .= '&article_id=' . request('article_id');
            }
            return redirect()->to($realPayUrl);
        }
    }
}
