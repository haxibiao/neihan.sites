<?php

namespace App\Http\Controllers;

use App\Compare;
use App\Match;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CompareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compares = Compare::all();
        return view('compare.index')
            ->withCompares($compares);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $options = [
            "6"  => 6,
            "8"  => 8,
            "12" => 12,
            "16" => 16,
        ];

        return view('compare.create')
            ->withOptions($options);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $compare           = new Compare($request->all());
        $compare->start_at = Carbon::now()->toDateTimeString();
        $compare->save();

        return redirect()->to('/compare');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compare = Compare::with('teams')->with('matchs')->findOrFail($id);
        $teams   = $compare->teams;

        $match       = [];
        $matchs['1'] = $compare->matchs()->where('round', 1)->get();
        $matchs['2'] = $compare->matchs()->where('round', 2)->get();
        $matchs['3'] = $compare->matchs()->where('round', 3)->get();
        $matchs['4'] = $compare->matchs()->where('round', 4)->get();

        $this->makeTeamEliminateMatches($compare);

        return view('compare.show')
            ->withCompare($compare)
            ->withTeams($teams)
            ->withMatchs($matchs);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('compare.edit');
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
        //
    }

    public function makeTeamEliminateMatches($compare)
    {
        //根据传入的赛季实时获取最新team数据,按照积分排序
        $teams = $compare->teams()->orderBy('team_score', 'desc')->get();
        //取前N位为胜者组成员,此时其他则为败者组.
        $n           = $teams->count() / 2;
        $team_winner = $teams->take($n);
        //倒转collection 并返回前N个
        $team_lose = $teams->reverse()->take($n);

        //创建第二轮胜者组match
        foreach ($team_winner as $team_w) {
            if ($team_w->out || ($team_w->status) != 1) {
                continue;
            }
            $team_except = $team_winner
                ->whereNotIn('id', $team_w->id)->random();
            $team_except->status =2;
            $team_except->group ='win';
            $team_except->update();  
            $team_w->status =2;
            $team_w->group ='win';
            $team_w->update();
            $match = new Match();
            $match->round =2;
            $match->type ='淘汰赛';
            $match->TA =$team_w->id;
            $match->TB =$team_except->id;
            $match->compare_id=$compare->id;
            $match->start_at=Carbon::now()->toDateTimeString();
            $match->save();
        }
        //第二轮败者组match
        foreach ($team_lose as $team_w) {
            if ($team_w->out || ($team_w->status) != 1) {
                continue;
            }
            $team_except = $team_lose
                ->whereNotIn('id', $team_w->id)->random();
            $team_except->status =2;
            $team_except->group ='lose';
            $team_except->update();  
            $team_w->status =2;
            $team_w->group ='lose';
            $team_w->update();
            $match = new Match();
            $match->round =2;
            $match->type ='淘汰赛';
            $match->TA =$team_w->id;
            $match->TB =$team_except->id;
            $match->compare_id=$compare->id;
            $match->start_at=Carbon::now()->toDateTimeString();
            $match->save();
        }

    }
}
