<?php

namespace App\Jobs;

use App\Issue;
use App\Transaction;
use App\Notifications\QuestionBonused;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class BonusAnswers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    protected $issue;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    public function handle()
    {
        $issue = $this->issue;
        //没回答 或者已经分奖金，跳过
        if (!$issue->resolutions()->count() || $issue->isAnswered()) {
            return;
        }

        //自动奖励前10个回答
        $top10Resolutions= $issue->answers()->take(10)->get();
        //分奖金(保留两位，到分位)，发消息
        $bonus_each = floor($issue->bonus / $top10Resolutions->count() * 100) / 100;
        //事务
        DB::beginTransaction();
        try{
            foreach ($top10Resolutions as $answer) {
                $answer->bonus = $bonus_each;
                $answer->save();

                //到账
                Transaction::create([
                    'user_id' => $answer->user->id,
                    'type'    => '付费回答奖励',
                    'log'     => $issue->link() . '选中了您的回答',
                    'amount'  => $bonus_each,
                    'status'  => '已到账',
                    'balance' => $answer->user->balance + $bonus_each,
                ]);

                //消息
                $answer->user->notify(new QuestionBonused($issue->user, $issue));
            }
            //标记已回答
            $issue->answered_ids = implode($top10Resolutions->pluck('id')->toArray(), ',');
            $issue->save();
            //事务提交
            DB::commit();  
        }catch(\Exception $e){
            DB::rollBack();//回滚
            throw new \Exception($e);
            
        }
        
    }
}
