<?php

namespace App\Http\Controllers\Api;

use App\Issue;
use App\IssueInvite;
use App\Notifications\QuestionBonused;
use App\Notifications\QuestionDelete;
use App\Notifications\QuestionInvited;
use App\Resolution;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    //相似问答
    public function suggest(Request $request)
    {
        $questions = [];
        if (!empty(request('q'))) {
            $questions = Issue::where('title', 'like', '%' . request('q') . '%')->take(10)->get();
        }
        return $questions;
    }

    //问题可以邀请用户列表,七天内只能邀请一次
    public function questionUninvited(Request $request, $issue_id)
    {
        $user = Auth::guard('api')->user();
        //获取当前七天前邀请的用户
        $inviteIds = $user->issueInvites()->where('issue_id', $issue_id)
            ->whereRaw('DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(updated_at)')
            ->pluck('invite_user_id')->toArray();
        //获取当前关注的用户 并排除七天内邀请过的用户
        $followUserIds = $user->followingUsers->whereNotIn('followed_id', $inviteIds)->pluck('followed_id')->toArray();

        $users = [];
        if ($followUserIds) {
            $users = User::whereIn('id', $followUserIds)->get();
            foreach ($users as $user) {
                $user->fillForJs();
                $user->invited = 0;
            }
        }

        return $users;
    }

    //邀请用户
    public function questionInvite(Request $request, $qid, $invite_uid)
    {
        $user        = $request->user();
        $invite_user = User::find($invite_uid);
        if ($invite_user) {
            $invite = IssueInvite::firstOrNew([
                'user_id'        => $user->id,
                'issue_id'    => $qid,
                'invite_user_id' => $invite_uid,
            ]);
            //避免重复发消息
            if (!$invite->id) {
                $invite_user->notify(new QuestionInvited($user->id, $qid));
            } else {
                //手动更新下updated_at
                $invite->updated_at = $invite->freshTimestamp();
            }

            $invite->save();
        }
        return $invite;
    }

    //采纳答案
    public function answered(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);

        //确保采纳了一些答案
        if (is_array($request->answered_ids) && count($request->answered_ids)) {

            //fix error, vue send answered_ids as json array
            $resolution_ids = [];
            foreach ($request->answered_ids as $resolution_id) {
                if (is_numeric($resolution_id)) {
                    $resolution_ids[] = $resolution_id;
                } else {
                    //TODO ???
                    $resolution_ids[] = $resolution_id['answerId'];
                }
            }

            $issue->resolution_ids = implode($resolution_ids, ',');
            $issue->closed       = true;
            $issue->save();

            //选中的回答者分奖金，发消息
            $bonus_each = floor($issue->bonus / count($request->answered_ids) * 100) / 100;
            foreach ($issue->selectedAnswers() as $answer) {
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
        }

        return $issue;
    }

    public function question(Request $request, $id)
    {
        $question = Issue::findOrFail($id);
        return $question;
    }

    public function favoriteQuestion(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        $issue->count_favorites++;
        $issue->save();
        $issue->favorited = 1;

        //re-use favorite controller
        $controller = new \App\Http\Controllers\Api\FavoriteController();
        $controller->toggle($request, $id, 'questions');

        return $issue;
    }

    public function reportQuestion(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        $issue->count_reports++;
        $issue->save();
        $issue->reported = 1;
        return $issue;
    }

    public function answer(Request $request, $id)
    {
        $resolution = Resolution::findOrFail($id);
        return $resolution;
    }

    public function likeAnswer(Request $request, $id)
    {
        $resolution = Resolution::findOrFail($id);
        $resolution->count_likes++;
        $resolution->save();
        $resolution->liked = 1;
        return $resolution;
    }

    public function unlikeAnswer(Request $request, $id)
    {
        $resolution = Resolution::findOrFail($id);
        $resolution->count_unlikes++;
        $resolution->save();
        $resolution->unliked = 1;
        return $resolution;
    }

    public function reportAnswer(Request $request, $id)
    {
        $resolution = Resolution::findOrFail($id);
        $resolution->count_reports++;
        $resolution->save();
        $resolution->reported = 1;
        return $resolution;
    }

    public function deleteAnswer(Request $request, $id)
    {
        $resolution         = Resolution::findOrFail($id);
        $resolution->status = -1;
        $resolution->save();
        $resolution->deleted = 1;
        return $resolution;
    }
    public function delete(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        if ($issue->bonus > 0 && !$issue->close) {
            //自动奖励前10个回答
            $top10Resolutions = $issue->resolutions()->take(10)->get();
            //分奖金(保留两位，到分位)，发消息r

            if (!$top10Resolutions) {
                $bonus_each = floor($issue->bonus / $top10Resolutions->count() * 100) / 100;
                foreach ($top10Resolutions as $resolution) {
                    $resolution->bonus = $bonus_each;
                    $resolution->save();
                    //到账
                    Transaction::create([
                        'user_id' => $resolution->user->id,
                        'type'    => '付费回答奖励',
                        'log'     => $issue->link() . '选中了您的回答',
                        'amount'  => $bonus_each,
                        'status'  => '已到账',
                        'balance' => $resolution->user->balance + $bonus_each,
                    ]);
                    //消息
                    $resolution->user->notify(new QuestionBonused($issue->user, $issue));
                    //通知已经结账
                    $issue->user->notify(new QuestionDelete($issue));
                }
                //标记已回答
                $issue->resolutions_id = implode($top10Resolutions->pluck('id')->toArray(), ',');
            } else {
                Transaction::create([
                    'user_id' => $issue->user->id,
                    'type'    => '退回问题奖金',
                    'log'     => $issue->link() . '您的问题无人回答',
                    'amount'  => $issue->bonus,
                    'status'  => '已到账',
                    'balance' => $issue->user->balance + $issue->bonus,
                ]);

                $issue->user->notify(new QuestionDelete($issue));

            }
        }

        $issue->status = -1;
        $issue->save();
        if ($issue->bonus > 0) {
            if ($issue->answered_ids) {
                $issue->message = "您的问题已删除,并且已结账";
            } else {
                $issue->message = "您的问题已删除,奖金已退回";
            }

        } else {
            $issue->message = "您的问题已删除";
        }
        $issue->deleted = 1;
        return $issue;
    }

    public function commend(Request $request)
    {
        $issues = Issue::orderBy('hits', 'desc')->where('image1', '<>', null)->take(3)->get();

        foreach ($issues as $issue) {
            $issue->image1 = $issue->image1();
        }

        return $issues;
    }
}
