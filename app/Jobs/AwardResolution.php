<?php

namespace App\Jobs;

use App\Gold;
use App\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AwardResolution implements ShouldQueue
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $issue   = $this->issue;
        $article = $issue->article;

        if (!$article) {
            return;
        }

        if ($issue->gold <= 0) {
            return;
        }

        //该问答已经下架
        if ($issue->trashed() || $article->status < 1) {
            return;
        }

        //问题已经被解决
        if ($issue->closed) {
            return;
        }

        //选择不同用户前三条为解决方案&跳过自己的回答
        $comments = $article->comments()
            ->where('user_id', '<>', $issue->user_id)
            ->groupBy('user_id', 'id')
            ->orderBy('id', 'desc')
            ->limit(3);

        //期限内没有人评论该回答
        if (!$comments) {
            //金币原路退回
            $user = $issue->user;
            // Gold::makeIncome($user, $issue->gold, '问答过期金币退回');
            $user->goldWallet->changeGold($issue->gold, '问答过期金币退回');
            return;
        }

        DB::beginTransaction();
        try {
            $gold       = $issue->gold;
            $individual = $gold / count($comments);

            foreach ($comments as $comment) {
                //注释的原因：与PM沟通后系统自动采纳评论不放入问题的解决方案
                //                $solution = new Solution();
                //                $solution->answer   = $comment->body;
                //                $solution->user_id  = $comment->user_id;
                //                $solution->issue_id = $article->issue_id;
                //                $solution->gold = $individual;
                //                $solution->save();

                //评论被采纳
                $comment->is_accept = true;
                $comment->save();

                $user = $comment->user;

                // Gold::makeIncome($user, $individual, '答案被采纳奖励');
                $user->goldWallet->changeGold($individual, '答案被采纳奖励');

                //评论被采纳
                $user->notify(new \App\Notifications\CommentAccepted($comment, $issue->user));
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
        }
    }
}
