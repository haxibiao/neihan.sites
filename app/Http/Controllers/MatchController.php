<?php

namespace App\Http\Controllers;

use App\Compare;
use App\Match;
use App\Team;
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

        //更新Team下的积分
        $score   = explode(':', $match->score);
        $score_a = $score['0'];
        $score_b = $score['1'];

        $team_a             = Team::findOrFail($match->TA);
        $team_a->team_score = $team_a->team_score + $score_a;
        $team_a->update();
        $team_b             = Team::findOrFail($match->TB);
        $team_b->team_score = $team_b->team_score + $score_b;
        $team_b->update();
        $match->update();

        return redirect()->to("/compare/$match->compare_id");
    }

    //自动创建小组赛
    public function makeTeamGroupMatches(Request $request)
    {
        $compare = Compare::findOrFail($request->get('compare_id'));
        $teams   = $compare->teams;

        foreach ($teams as $team) {
            $teams_except_ids = $teams
                ->where('id', '>', $team->id)
                ->where('group', $team->group)->pluck('id');
            foreach ($teams_except_ids as $team_b_id) {
                $match             = new Match();
                $match->type       = '小组赛';
                $match->TA         = $team->id;
                $match->TB         = $team_b_id;
                $match->status     = "比赛中";
                $match->round      = 1;
                $match->compare_id = $request->get('compare_id');
                $match->save();
            }
        }
        return redirect()->to('/compare/' . request('compare_id'));
    }

    //小组赛如果结束才允许开启淘汰赛

    public function makeTeamEliminateMatches(Request $request)
    {

    }
}
