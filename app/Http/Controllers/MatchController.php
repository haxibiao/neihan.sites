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

        //获取当前赛季的分组情况
        $teams_count= $teams->count();
        if($teams_count <= 8){
            $teams_a=$teams->where('group','A');
            dd($teams_a);
        }
    }
}
