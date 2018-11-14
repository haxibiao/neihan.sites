<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Notifications\QuestionBonused;
use App\Notifications\QuestionDelete;
use App\Notifications\QuestionInvited;
use App\Question;
use App\QuestionInvite;
use App\Transaction;
use App\User;
use Auth;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    //相似问答
    public function suggest(Request $request)
    {
        $questions = [];
        if (!empty(request('q'))) {
            $questions = Question::where('title', 'like', '%' . request('q') . '%')->take(10)->get();
        }
        return $questions;
    }

    //问题可以邀请用户列表,七天内只能邀请一次
    public function questionUninvited(Request $request, $question_id)
    {
        $user = Auth::guard('api')->user();
        //获取当前七天前邀请的用户
        $inviteIds = $user->questionInvites()->where('question_id', $question_id)
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
            $invite = QuestionInvite::firstOrNew([
                'user_id'        => $user->id,
                'question_id'    => $qid,
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
        $question = Question::findOrFail($id);

        //确保采纳了一些答案
        if (is_array($request->answered_ids) && count($request->answered_ids)) {

            //fix error, vue send answered_ids as json array
            $answered_ids = [];
            foreach ($request->answered_ids as $answered_id) {
                if (is_numeric($answered_id)) {
                    $answered_ids[] = $answered_id;
                } else {
                    $answered_ids[] = $answered_id['answerId'];
                }
            }

            $question->answered_ids = implode($answered_ids, ',');
            $question->closed       = 1;
            $question->save();

            //选中的回答者分奖金，发消息
            $bonus_each = floor($question->bonus / count($request->answered_ids) * 100) / 100;
            foreach ($question->selectedAnswers() as $answer) {
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
        }

        return $question;
    }

    public function question(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        return $question;
    }

    public function favoriteQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->count_favorites++;
        $question->save();
        $question->favorited = 1;

        //re-use favorite controller
        $controller = new \App\Http\Controllers\Api\FavoriteController();
        $controller->toggle($request, $id, 'questions');

        return $question;
    }

    public function reportQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->count_reports++;
        $question->save();
        $question->reported = 1;
        return $question;
    }

    public function answer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        return $answer;
    }

    public function likeAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->count_likes++;
        $answer->save();
        $answer->liked = 1;
        return $answer;
    }

    public function unlikeAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->count_unlikes++;
        $answer->save();
        $answer->unliked = 1;
        return $answer;
    }

    public function reportAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->count_reports++;
        $answer->save();
        $answer->reported = 1;
        return $answer;
    }

    public function deleteAnswer(Request $request, $id)
    {
        $answer         = Answer::findOrFail($id);
        $answer->status = -1;
        $answer->save();
        $answer->deleted = 1;
        return $answer;
    }
    public function delete(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        if ($question->bonus > 0 && !$question->close) {
            //自动奖励前10个回答
            $top10Answers = $question->answers()->take(10)->get();
            //分奖金(保留两位，到分位)，发消息r

            if (!$top10Answers) {
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
                    //通知已经结账
                    $question->user->notify(new QuestionDelete($question));
                }
                //标记已回答
                $question->answered_ids = implode($top10Answers->pluck('id')->toArray(), ',');
            } else {
                Transaction::create([
                    'user_id' => $question->user->id,
                    'type'    => '退回问题奖金',
                    'log'     => $question->link() . '您的问题无人回答',
                    'amount'  => $question->bonus,
                    'status'  => '已到账',
                    'balance' => $question->user->balance() + $question->bonus,
                ]);

                $question->user->notify(new QuestionDelete($question));

            }
        }

        $question->status = -1;
        $question->save();
        if ($question->bonus > 0) {
            if ($question->answered_ids) {
                $question->message = "您的问题已删除,并且已结账";
            } else {
                $question->message = "您的问题已删除,奖金已退回";
            }

        } else {
            $question->message = "您的问题已删除";
        }
        $question->deleted = 1;
        return $question;
    }

    public function commend(Request $request)
    {
        $questions = Question::orderBy('hits', 'desc')->where('image1', '<>', null)->take(3)->get();

        foreach ($questions as $question) {
            $question->image1 = $question->image1();
        }

        return $questions;
    }
}
