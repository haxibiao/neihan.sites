<?php

namespace App\Http\Controllers;

use App\Article;
use App\Transaction;
use Auth;
use DB;
use App\Question;
use App\Tip;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function pay()
    {
        $amount = request('amount');

        if (Auth::check() && Auth::user()->balance() >= $amount) {
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

                        Tip::create([
                            'user_id'=>Auth::user()->id,
                            'tipable_type' =>'articles',
                            'tipable_id'=>$article->id,
                            'amount'=>$amount,
                        ]);
                    }
                }, 3);
            }
            if(request('question_id')){
                DB::transaction(function(){
                     $type ='付费问题';
                     $amount =request('amount');
                     $question =Question::with('user')->find(request('question_id'));
                     if($question){
                         $log = '你创建了付费问题'.$question->link().'付费金额:'.$amount.'元';
                          Transaction::create([
                              'user_id'=> Auth::id(),
                              'type' =>$type,
                              'log'=>$log,
                              'amount'=>$amount,
                              'status'=>'已到账',
                              'balance'=> Auth::user()->balance() - $amount,
                          ]);
                         $question->bonus =$amount;
                         $question->status =1 ;
                         $question->save();
                     }
                },3);
            }
            return redirect()->to('/wallet');
        } else {
            //未登录或者不够钱的-=
            $realPayUrl = '/alipay/wap/pay?amount=' . $amount . '&type=' . request('type');
            if (request('article_id')) {
                $realPayUrl .= '&article_id=' . request('article_id');
            }
            if(request('question_id')){
                $realPayUrl .= '&question_id=' . request('question_id');
            }
            return redirect()->to($realPayUrl);
        }
    }
}
