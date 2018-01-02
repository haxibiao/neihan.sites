<?php

namespace App\Http\Controllers;

use App\Compare;
use App\Match;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $compares = Compare::orderBy('id')->pluck('name', 'id');
        return view('compare.match.create')
            ->withCompares($compares);
    }

    public function store(Request $request)
    {
        $match = new Match($request->all());
        $match->save();

        return redirect()->to("/compare/$match->compare_id");
    }

    public function edit(Request $request, $id)
    {
        $match = Match::findOrFail($id);

        return view('compare.match.edit')
            ->withMatch($match);
    }

    public function update(Request $request, $id)
    {
        $match         = Match::findOrFail($id);
        $match->winner = $request->winner;
        $match->score  = $request->score;
        $match->update();

        return redirect()->to("/compare/$match->compare_id");
    }

    public function makeTeamMatches(Request $request)
    {
        $compare_id = $request->get('compare_id');
        $compare    = Compare::findOrFail($compare_id);
        $teams      = $compare->teams;

        $take    = $teams->count() / 2;
        $team_as = $teams->take($take);
        foreach ($team_as as $team_a) {
            $team_id[] = $team_a->id;
        }
        $team_b = $team_a->whereNotIn('id', $team_id)->get();

        //排列算法
        $count_macthes = $teams->count() * ($teams->count() - 1);

        //总共需要创建的对局数量
        for ($i = 1; $i <= $count_macthes; $i++) {
            $match = new Match();
            $match->round = 1;
            $match->type ='小组赛';
             foreach($teams as $team){
                     if($team->match_history() < 2){
                          $match_a= $team->id;
                     }
             }
            $match->TA= $match_a;
            $match->TB= $match_b;
        }
    }
}
