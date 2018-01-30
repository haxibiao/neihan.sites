<?php

namespace App\Jobs;

use App\Question;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class PayQuestion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    //max line
    protected $tries = 3;

    protected $question;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($question_id)
    {
        $this->question = Question::find($question_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $question = $this->question;
        // $now      = Carbon::now()->toDateTimeString();
        if (!empty($question->deadline)) {
            $this->check_deadline($question);
        }
    }

    public function check_deadline($question)
    {
        $answers            = $question->answers()->take(10)->get();
        $count_answer       = $answers->count();
        $question->deadline = null;
        //实在没有回答 返回钱款给提问者
        if ($count_answer > 0) {
            $amount = $question->bonus / $count_answer;
        } else {
            $this->return_money($question);
            return $question;
        }
        foreach ($answers as $answer) {
            $type        = '回答打赏';
            $answer->tip = $amount;
            $answer->save();
            $user = $answer->user;
            $log  = '你的回答' . $answer->description() . '获得金额:' . $amount . '元';
            Transaction::create([
                'user_id' => $user->id,
                'type'    => $type,
                'log'     => $log,
                'amount'  => $amount,
                'status'  => '已到账',
                'balance' => $user->balance() + $amount,
            ]);
        }
        return $question;
    }

    public function return_money($question)
    {
        $type   = "退回金额";
        $amount = $question->bonus;
        $user   = $question->user;
        $log    = '你的问题' . $question->link() . '超时且没有一人回答退回金额:' . $amount . '元';
        Transaction::create([
            'user_id' => $user->id,
            'type'    => $type,
            'log'     => $log,
            'amount'  => $amount,
            'status'  => '已到账',
            'balance' => $user->balance() + $amount,
        ]);
        return $question;
    }
}
