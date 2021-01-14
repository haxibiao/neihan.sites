<?php

namespace App\Http\Controllers;

use App\Category;
use App\Issue;
use App\Jobs\BonusAnswers;
use App\Notifications\QuestionBonused;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class IssueController extends Controller
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
        $question             = Issue::findOrFail(request('question_id'));
        $add                  = "\r\n\r\n" . now() . '补充：' . "\r\n" . request('answer');
        $question->background = $question->background . $add;
        $question->save();
        return redirect()->to('/question/' . $question->id);
    }

    //付费的
    public function bonused()
    {
        $category = null;
        $qb       = Issue::with('latestResolution.article')
            ->orderBy('closed')
            ->orderBy('id', 'desc')
            ->where('bonus', '>', 0);
        $questions  = $qb->paginate(10);
        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();
        $hot        = Issue::with('latestResolution.article')->orderBy('hits', 'desc')->where('status', '>=', 0)->take(3)->get();
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
        $qb = Issue::with('latestResolution.article')
            ->orderBy('closed')
            ->orderBy('id', 'desc');
        $category = null;
        if (request('cid')) {
            $category = Category::findOrFail(request('cid'));
            $qb       = $category->issues()->with('latestResolution.article')->orderBy('id', 'desc');
        }
        $questions  = $qb->paginate(10);
        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();
        $hot        = Issue::with('latestResolution.article')->orderBy('hits', 'desc')->take(3)->get();
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
        $user  = $request->user();
        $issue = new Issue($request->all());
        //付费问题
        $issue->save();
        if (request()->is_pay) {
            //检查账户钱是否够bonus
            if ($user->balance < $issue->bonus) {
                //先直接跳转到充值界面（钱包页）
                //这里前段已经直接判断账户余额，并要求去充值了，这里只是双保险，没钱不能发出付费金额相应的问题
                return redirect()->to('/wallet');
            }

            //先把提问者的钱扣到系统
            Transaction::create([
                'user_id'   => $user->id,
                'wallet_id' => $user->wallet->id,
                'type'      => '付费提问',
                'log'       => $issue->link() . '的付费咨询金',
                'amount'    => $issue->bonus,
                'status'    => '已支付',
                'balance'   => $user->balance - $issue->bonus,
            ]);

            if ($issue->deadline > 0) {
                //有个job定时处理分奖金
                BonusAnswers::dispatch($issue)
                    ->delay(now()->addDays($issue->deadline));
            }
        } else {
            //免费问题
            $issue->bonus    = 0;
            $issue->deadline = null;
        }
        $issue->save();
        return redirect()->to('/question/' . $issue->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $issue = Issue::with('resolutions')->with('user')->with('categories')->findOrFail($id);
        $issue->hits++;
        $issue->save();

        //guess you like: 取当前问提分类下的3篇文章，如果没有文章，就随机取
        $guess = new Collection([]);
        foreach ($issue->categories as $category) {
            $guess = $guess->merge($category->issues()->orderBy('hits', 'desc')->take(3)->get());
        }
        if ($guess->isEmpty()) {
            if (Issue::count() > 3) {
                $guess = Issue::orderBy('hits', 'desc')->take(10)->get()->random(3);
            } else {
                $guess = Issue::all();
            }
        } else {
            if ($guess->count() > 3) {
                $guess = $guess->random(3);
            }
        }

        $resolutions = $issue->resolutions()->paginate(10);
        return view('question.show')
            ->withGuess($guess)
            ->withAnswers($resolutions)
            ->withQuestion($issue);
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
        $issue = Issue::findOrFail($id);
        if ($issue->bonus > 0) {
            //自动奖励前10个回答
            $top10Resolutions = $issue->resolutions()->take(10)->get();
            //分奖金(保留两位，到分位)，发消息
            if (!$top10Resolutions) {
                $bonus_each = floor($issue->bonus / $top10Resolutions->count() * 100) / 100;
                foreach ($top10Resolutions as $resolution) {
                    $resolution->bonus = $bonus_each;
                    $resolution->save();

                    //到账
                    Transaction::create([
                        'user_id'   => $resolution->user->id,
                        'wallet_id' => $user->wallet->id,
                        'type'      => '付费回答奖励',
                        'log'       => $resolution->link() . '选中了您的回答',
                        'amount'    => $bonus_each,
                        'status'    => '已到账',
                        'balance'   => $resolution->user->balance + $bonus_each,
                    ]);

                    //消息
                    $resolution->user->notify(new QuestionBonused($issue->user, $issue));
                }
                //标记已回答
                $issue->resolution_ids = implode($top10Resolutions->pluck('id')->toArray(), ',');
            } else {
                Transaction::create([
                    'user_id'   => $issue->user->id,
                    'type'      => '退回问题奖金',
                    'wallet_id' => $user->wallet->id,
                    'log'       => $issue->link() . '您的问题无人回答',
                    'amount'    => $issue->bonus,
                    'status'    => '已到账',
                    'balance'   => $issue->user->balance + $issue->bonus,
                ]);
            }
        }
        $issue->status = -1;
        $issue->save();

        return redirect()->back();

    }
}
