<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Category;
use App\Http\Requests\QuestionRequest;
use App\Jobs\PayQuestion;
use App\Question;
use App\Traits\QuestionControllerFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuestionController extends Controller
{
    //计算相关函数全部抽离至接口中
    use QuestionControllerFunction;

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

        if (request('cid') > 0) {
            $category = Category::findOrFail(request('cid'));
            $qb       = $category->questions()->with('latestAnswer.article')->orderBy('id', 'desc');
        }

        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();
        
        $questions = $qb->paginate(10);

        // if (count($categories) < 7) {
        //     $categories = Category::with('questions')
        //         ->orderBy('id', 'desc')
        //         ->take(7)
        //         ->get();
        // }

        // null defalut 0
        if(request('cid')==-1){
            $questions=$this->pay_question();
            $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();
            $hot        = Question::with('latestAnswer.article')->orderBy('hits', 'desc')->take(3)->get();
        }
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
            PayQuestion::dispatch($question->id)
                ->delay(now()->addHours(24 * $request->deadline));

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

        $question->save();

        $categories=$question->categories;
        $answers = Answer::with('user')->where('question_id', $question->id)->where('status', '>', 0)->orderBy('id', 'desc')->paginate(10);

        $qb          = Question::with('latestAnswer.article')->with('user')->orderBy('id', 'desc');
        $data['hot'] = $qb->orderBy('hits', 'desc')->take(3)->get();

        return view('interlocution.show')
            ->withCategories($categories)
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
}
