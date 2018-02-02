<?php

namespace App\Traits;

use App\Answer;
use App\Question;
use App\Traits\QuestionControllerFunction;
use App\Transaction;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

trait QuestionControllerFunction
{
    public function pay_tip(Request $request)
    {
        $question = Question::with('user')->findOrFail($request->question_id);
        $now      = Carbon::now()->toDateTimeString();
        $user     = Auth::user();
        if (is_array($request->answer_ids) && $question->deadline && $question->deadline > $now && $question->user->id == $user->id) {
            $pay_count = count($request->answer_ids);
            //每位回答者应该收到的钱
            $amount = $question->bonus / $pay_count;
            foreach ($request->answer_ids as $answer_id) {
                $type        = '回答打赏';
                $answer      = Answer::find($answer_id);
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
            $question->deadline = null;
            $question->save();
            return redirect()->to('/question/' . $question->id);
        } else {
            return abort(403);
        }
    }

    public function pay_question()
    {
       $questions=Question::with('latestAnswer.article')
       ->where('bonus','>',0)
       ->orderBy('deadline','desc')
       ->orderBy('id','desc')
       ->paginate(10);
       return $questions;
    }
}
