<?php

namespace App\Jobs;

use App\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BonusAnswers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    protected $question;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $question = $this->question;
        //没回答 或者已经分奖金，跳过
        if (!$question->answers()->count() || $question->isAnswered()) {
            return;
        }

        //自动奖励前10个回答
        $top10Answers = $question->answers()->take(10)->get();
        //分奖金(保留两位，到分位)，发消息
        $bonus_each = floor($question->bonus / $top10Answers->count() * 100) / 100;
        foreach ($top10Answers as $answer) {
            $answer->bonus = $bonus_each;
            $answer->save();

            //到账
            Transaction::create([
                'user_id' => $answer->user->id,
                'type'    => '付费回答奖励',
                'log'     => $question->link() . '选中了您的回答',
                'amount'  => $bonus_each,
                'status'  => '已到账',
                'balance' => $answer->user->balance() + $bonus_each,
            ]);

            //消息
            $answer->user->notify(new QuestionBonused($question->user, $question));
        }
        //标记已回答
        $question->answered_ids = implode($top10Answers->pluck('id')->toArray(), ',');
        $question->save();
    }
}
