<?php

namespace App\Http\Controllers\Alipay;

use App;
use App\Article;
use App\Http\Controllers\Controller;
use App\Transaction;
use Auth;
use Omnipay\Omnipay;
use App\Question;

class WapController extends Controller
{
    public function gateway()
    {
        /**
         * @var AopAppGateway $gateway
         */
        $gateway = Omnipay::create('Alipay_AopWap');
        $gateway->setSignType('RSA2'); // RSA/RSA2/MD5

        $gateway->setAppId(config('pay')['alipay']['app_id']);
        $gateway->setPrivateKey(config('pay')['alipay']['private_key']);
        $gateway->setAlipayPublicKey(config('pay')['alipay']['public_key']);

        $dev = App::environment('local') ? 'l.' : '';

        $gateway->setReturnUrl('http://' . $dev . get_domain() . '/alipay/wap/return');
        $gateway->setNotifyUrl('https://' . get_domain() . '/alipay/wap/notify');
        return $gateway;
    }

    public function wapPay()
    {
        /**
         * @var AopTradePagePayResponse $response
         */

        $amount = 0.01;
        if (!empty($_GET['amount'])) {
            $amount = $_GET['amount'];
        }
        $type = '充值';
        if (request('type') == 'tip') {
            $type = '打赏';
        }
        if(request('type')=='question'){
            $type='付费问题';
        }
        $subject = $type;

        if (Auth::check()) {
            $user     = Auth::user();
            $log      = '';
            $tran_id1 = '';
            $tran_id2 = '';
            if ($type == '打赏' && request('article_id')) {
                $article = Article::with('user')->find(request('article_id'));
                if ($article) {
                    $subject = $type . $article->title;
                    $log     = '向' . $article->user->link() . '的文章' . $article->link() . '打赏' . $amount . '元';
                    $log2    = $user->link() . '向您的文章' . $article->link() . '打赏' . $amount . '元';

                    $transaction = Transaction::create([
                        'user_id' => $article->user->id,
                        'type'    => $type,
                        'log'     => $log2,
                        'amount'  => $amount,
                        'status'  => '未支付',
                        'balance' => $article->user->balance(),
                    ]);
                    $tran_id2 = $transaction->id;
                }
            }

            if($type =='付费问题' && request('question_id')){
                $question =Question::with('user')->find(request('question_id'));
                if($question){
                     $subject=$type.$question->title;
                     $log  ='你创建了付费问题'.$question->link().'付费金额:'.$amount.'元';
                }
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type'    => $type,
                'log'     => $log,
                'amount'  => $amount,
                'status'  => '未支付',
                'balance' => $user->balance(),
            ]);

            $tran_id1     = $transaction->id;
            $out_trade_no = $this->encodeOutTradeNo(request('type') . '-' . $tran_id1 . '-' . $tran_id2);
            if($type =='付费问题' && request('question_id')){
                    $tran_id     = $transaction->id;
                    $out_trade_no = $this->encodeOutTradeNo(request('type') . '-' . $tran_id.'-'.request('question_id'));
            }
        } else {
            //未登录游客直接打赏
            $out_trade_no = $this->encodeOutTradeNo(request('article_id'));
        }



        $response = $this->gateway()->purchase()->setBizContent([
            'subject'      => $subject,
            'out_trade_no' => $out_trade_no,
            'total_amount' => $amount,
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ])->send();

        $url = $response->getRedirectUrl();
        return redirect($url);
    }

    public function wapReturn()
    {
        $request = $this->gateway()->completePurchase();
        $request->setParams(array_merge($_POST, $_GET)); //Don't use $_REQUEST for may contain $_COOKIE

        /**
         * @var AopCompletePurchaseResponse $response
         */
        try {
            $response = $request->send();

            if ($response->isPaid()) {
                if (Auth::check()) {
                    if ($this->makePaymentProcess($response)) {
                        return redirect()->to('/wallet');
                    }
                } else {
                    //未登录用户打赏后，回到文章
                    $out_trade_no = array_get($response->getData(), 'out_trade_no');
                    $out_trade_no = $this->decodeOutTradeNo($out_trade_no);
                    if (is_double(doubleval($out_trade_no))) {
                        $article_id = $out_trade_no;
                        $this->justTipForArticleUser($article_id, $response);
                        return redirect()->to("/article/$article_id");
                    }
                }

            } else {
                /**
                 * Payment is not successful
                 */
                die('fail'); //The notify response
            }
        } catch (Exception $e) {
            /**
             * Payment is not successful
             */
            die('fail'); //The notify response
        }
    }

    /**
     * 异步通知
     */
    public function wapNotify()
    {
        $request = $this->gateway()->completePurchase();
        $request->setParams(array_merge($_POST, $_GET)); //Don't use $_REQUEST for may contain $_COOKIE

        /**
         * @var AopTradeAppPayResponse $response
         */

        try {
            $response = $request->send();

            if ($response->isPaid()) {
                $process      = false;
                $out_trade_no = array_get($response->getData(), 'out_trade_no');
                $out_trade_no = $this->decodeOutTradeNo($out_trade_no);
                if (is_double(doubleval($out_trade_no))) {
                    $article_id = $out_trade_no;
                    $process    = $this->justTipForArticleUser($article_id);
                } else {
                    $process = $this->makePaymentProcess($response);
                }
                if ($process) {
                    die('success');
                } else {
                    die('fail');
                }
                //The notify response should be 'success' only
            } else {
                /**
                 * Payment is not successful
                 */
                die('fail'); //The notify response
            }
        } catch (Exception $e) {
            /**
             * Payment is not successful
             */
            die('fail'); //The notify response
        }
    }

    public function justTipForArticleUser($article_id, $response)
    {
        $article = Article::find($article_id);
        if ($article) {
            $amount = array_get($response->getData(), 'total_amount');
            return Transaction::create([
                'user_id' => $article->user->id,
                'type'    => '打赏',
                'log'     => '未登录用户打赏您的文章' . $article->link(),
                'amount'  => $amount,
                'status'  => '已到账',
                'balance' => $article->user->balance() + $amount,
            ]);
        }
        return false;
    }

    public function encodeOutTradeNo($out_trade_no)
    {
        return date('YmdH') . 'a' . $out_trade_no;
    }

    public function decodeOutTradeNo($out_trade_no)
    {
        return str_replace(date('YmdH') . 'a', '', $out_trade_no);
    }

    public function makePaymentProcess($response)
    {
        $out_trade_no                     = array_get($response->getData(), 'out_trade_no');
        $out_trade_no                     = $this->decodeOutTradeNo($out_trade_no);
        if(str_contains($out_trade_no,'article')){
        list($type, $tran_id1, $tran_id2) = explode('-', $out_trade_no);
        $tran1                            = Transaction::find($tran_id1);
        }
        if(str_contains($out_trade_no,'question')){
                 list($type,$tran_id,$question_id)  =explode('-', $out_trade_no);
                 $question =Question::find($question_id);
                 $tran = Transaction::find($tran_id);
        }
        if (!empty($tran1)) {
            if ($type == 'tip') {
                $tran1->status = '已到账';
                // $tran1->balance = $transaction->balance - $transaction->amount;
                $tran1->save();
                if (!empty($tran_id2)) {
                    $tran2 = Transaction::find($tran_id2);
                    if ($tran2) {
                        if ($tran2->status != '已到账') {
                            $tran2->status  = '已到账';
                            $tran2->balance = $tran2->balance + $tran2->amount;
                            $tran2->save();
                        }
                    }
                }
            } else {
                $tran1->status  = '已到账';
                $tran1->balance = $tran1->balance + $tran1->amount;
                $tran1->save();
            }
            return true;
        }

        if(!empty($tran)){
            if($type =='question'){
                $tran->status='已到账';
                $tran->save();
                $question->bonus =$tran->amount;
                $question->status = 1;
                $question->save();
            }
            return true;
        }
        return false;
    }
}
