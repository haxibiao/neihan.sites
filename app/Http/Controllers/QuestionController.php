<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Category;
use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Transaction;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuestionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.editor')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $qb   = Question::with('latestAnswer.article')->with('user')->where('status', '>', 0)->orderBy('id', 'desc');

        if (request('cid')) {
            $category = Category::findOrFail(request('cid'));
            $qb       = $category->questions()->with('latestAnswer.article')->orderBy('id', 'desc');
        }

        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();

        $questions = $qb->paginate(10);

        if (count($categories) < 7) {
            $categories = Category::with('questions')
                ->orderBy('id', 'desc')
                ->take(7)
                ->get();
        }

        // null defalut 0

        foreach ($questions as $question) {
            $question->count_defalut();
        }

        $data['hot'] = $qb->orderBy('hits', 'desc')->take(3)->get();

        if (AjaxOrDebug() && request('page')) {
            foreach ($questions as $question) {
                $question->count_defalut();
                $question->relateImage = $question->relateImage();
                $question->deadline    = diffForHumansCN($question->deadline);
                if (!empty($question->latestAnswer)) {
                    $question->latestAnswer->answer = strip_tags($question->latestAnswer->answer);
                }
            }
            return $questions;
        }

        return view('interlocution.index')
            ->withData($data)
            ->withCategories($categories)
            ->withQuestions($questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $question        = new Question($request->all());
        $question->bonus = -1;
        if ($request->deadline) {
            $deadline           = Carbon::now()->addHours(24 * $request->deadline)->toDateTimeString();
            $question->deadline = $deadline;
        }

        if ($request->bonus) {
            $question->status = -1;
        }
        $question->save();
        if (!empty($request->bonus)) {
            $pay_url = "/pay?amount=$request->bonus&type=question&question_id=$question->id";
            return redirect()->to($pay_url);
        }
        return redirect()->to('/question/' . $question->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data     = [];
        $question = Question::with('answers')->with('user')->with('categories')->findOrFail($id);
        $question->hits++;

        //check deadline
        $now = Carbon::now()->toDateTimeString();
        if (!empty($question->deadline) && $question->deadline <= $now) {
            $this->check_deadline($question);
        }
        $question->save();

        $answers = Answer::with('user')->where('question_id', $question->id)->where('status', '>', 0)->orderBy('id', 'desc')->paginate(10);

        $qb          = Question::with('latestAnswer.article')->with('user')->orderBy('id', 'desc');
        $data['hot'] = $qb->orderBy('hits', 'desc')->take(3)->get();

        return view('interlocution.show')
            ->withAnswers($answers)
            ->withQuestion($question)
            ->withData($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $answers  = $question->answers;

        if ($answers->count() > 0) {
            foreach ($answers as $answer) {
                $answer->status = -1;
                $answer->save();
            }
        }

        $question->status = -1;
        $question->save();

        return redirect()->to('/question');
    }

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

    public function check_deadline($question)
    {
        $answers      = $question->answers()->take(10)->get();
        $count_answer = $answers->count();
        $question->deadline = null;
        //实在没有回答 返回钱款给提问者
        if($count_answer > 0){
           $amount       = $question->bonus / $count_answer;
        }else{
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
         $type ="退回金额";
         $amount =$question->bonus;
         $user = $question->user;
         $log  = '你的问题' . $question->link() . '超时且没有一人回答退回金额:' . $amount . '元';
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
