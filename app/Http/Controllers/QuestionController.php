<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Question;
use App\Answer;

class QuestionController extends Controller
{

    public function __construct(){
        $this->middleware('auth.editor')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        $qb = Question::with('latestAnswer.article')->with('user')->orderBy('id', 'desc');

        if (request('cid')) {
            $category = Category::findOrFail(request('cid'));
            $qb       = $category->questions()->with('latestAnswer.article')->orderBy('id', 'desc');
        }

        $categories = Category::where('count_questions', '>', 0)->orderBy('updated_at', 'desc')->take(7)->get();

        $questions =$qb->paginate(10);

        if(count($categories) <7){
          $categories=Category::with('questions')
            ->orderBy('id','desc')
            ->take(7)
            ->get();
        }

        $data['hot']=$qb->orderBy('hits','desc')->take(3)->get();

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
    public function store(Request $request)
    {
        $question =new Question($request->all());
        $question->save();
        return redirect()->to('/question/'.$question->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=[];
        $question =Question::with('answers')->with('user')->with('categories')->findOrFail($id);
        $question->hits++;
        $question->save();         

        $answers =Answer::with('user')->where('question_id',$question->id)->orderBy('id','desc')->paginate(10);

        return view('interlocution.show')
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
        $question =Question::findOrFail($id);
        $answers =$question->answers;

        if($answers->count()>0){
            foreach ($answers as $answer) {
                  $answer->delete();
            }
        }

        $question->delete();

        return redirect()->to('/question');
    }
}
