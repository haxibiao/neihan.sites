<?php

namespace App\Http\Controllers;

use App\Category;
use App\Jobs\BonusAnswers;
use App\Question;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QuestionController extends Controller
{
    //问题分类页
    public function categories()
    {
        $categories = Category::where('count_questions', '>', 0)->paginate(12);
        return view('question.more_categories')->withCategories($categories);
    }

    //问题信息补充

    public function add()
    {
        $question             = Question::findOrFail(request('question_id'));
        $add                  = "\r\n\r\n" . now() . '补充：' . "\r\n" . request('answer');
        $question->background = $question->background . $add;
        $question->save();
        return redirect()->to('/question/' . $question->id);
    }

    //付费的
    public function bonused()
    {
        $category = null;
        $qb       = Question::with('latestAnswer.article')
            ->orderBy('closed')
            ->orderBy('id', 'desc')
            ->where('status','>=',0)
            ->where('bonus', '>', 0);
        $questions  = $qb->paginate(10);
        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();
        $hot        = Question::with('latestAnswer.article')->orderBy('hits', 'desc')->where('status','>=',0)->take(3)->get();
        return view('question.index')
            ->withHot($hot)
            ->withCategory($category)
            ->withCategories($categories)
            ->withQuestions($questions);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qb = Question::with('latestAnswer.article')
            ->orderBy('closed')
            ->where('status','>=',0)
            ->orderBy('id', 'desc');
        $category = null;
        if (request('cid')) {
            $category = Category::findOrFail(request('cid'));
            $qb       = $category->questions()->with('latestAnswer.article')->where('status','>=',0)->orderBy('id', 'desc');
        }
        $questions  = $qb->paginate(10);
        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();
        $hot        = Question::with('latestAnswer.article')->orderBy('hits', 'desc')->where('status','>=',0)->take(3)->get();
        return view('question.index')
            ->withHot($hot)
            ->withCategory($category)
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
    public function store(Request $request)
    {
        $user     = $request->user();
        $question = new Question($request->all());
        //付费问题
        $question->save();
        if (request()->is_pay) {
            //检查账户钱是否够bonus
            if ($user->balance() < $question->bonus) {
                //先直接跳转到充值界面（钱包页）
                //这里前段已经直接判断账户余额，并要求去充值了，这里只是双保险，没钱不能发出付费金额相应的问题
                return redirect()->to('/wallet');
            }

            //先把提问者的钱扣到系统
            Transaction::create([
                'user_id' => $user->id,
                'type'    => '付费提问',
                'log'     => $question->link() . '的付费咨询金',
                'amount'  => $question->bonus,
                'status'  => '已支付',
                'balance' => $user->balance() - $question->bonus,
            ]);

            if ($question->deadline > 0) {
                //有个job定时处理分奖金
                BonusAnswers::dispatch($question)
                    ->delay(now()->addDays($question->deadline));
            }
        } else {
            //免费问题
            $question->bonus    = null;
            $question->deadline = null;
        }
        $question->save();
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
        $question = Question::with('answers')->with('user')->with('categories')->where('status','>=',0)->findOrFail($id);
        $question->hits++;
        $question->save();

        //guess you like: 取当前问提分类下的3篇文章，如果没有文章，就随机取
        $guess = new Collection([]);
        foreach ($question->categories as $category) {
            $guess = $guess->merge($category->questions()->orderBy('hits', 'desc')->take(3)->get());
        }
        if ($guess->isEmpty()) {
            if (Question::count() > 3) {
                $guess = Question::orderBy('hits', 'desc')->take(10)->get()->random(3);
            } else {
                $guess = Question::all();
            }
        } else {
            if ($guess->count() > 3) {
                $guess = $guess->random(3);
            }
        }

        $answers = $question->answers()->paginate(10);
        return view('question.show')
            ->withGuess($guess)
            ->withAnswers($answers)
            ->withQuestion($question);
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
        if ($question->bonus > 0) {
            //自动奖励前10个回答
            $top10Answers = $question->answers()->take(10)->get();
            //分奖金(保留两位，到分位)，发消息
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
            }
        }
        $question->status = -1;
        $question->save();

        return redirect()->back();

    }
}
