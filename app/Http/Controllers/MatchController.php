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
        $teams_count = $teams->count();
        if ($teams_count <= 8) {
            $teams_a     = $teams->where('group', 'A');
            $teams_b     = $teams->where('group', 'B');
            $teams_human = $teams_a->count();
            $this->create_match($teams_a,$teams_b, $teams_human,$compare_id);
        } else {
            $team_group = $this->team_group($teams_count, $teams);

        }
    }

    //根据不同的分组情况返回分组
    public function team_group($teams_count, $teams)
    {
        $teams_group = [];
        switch ($teams_count) {
            case 12:
                $teams_group['a'] = $teams->where('group', 'A');
                $teams_group['b'] = $teams->where('group', 'B');
                $teams_group['c'] = $teams->where('group', 'C');
                return $teams_group;
                break;

            case 16:
                $teams_group['a'] = $teams->where('group', 'A');
                $teams_group['b'] = $teams->where('group', 'B');
                $teams_group['c'] = $teams->where('group', 'C');
                $teams_group['d'] = $teams->where('group', 'D');
                return $teams_group;
                break;

            default:
                return response('你输入情况有错误,请检查',404);
                break;
        }

    }

    //创建刚开始的小组赛对局
    public function create_match($teams_a,$teams_b,$teams_human,$compare_id)
    {
        if ($teams_human == 3) {
            //需要进行的总对局数量排列算法.
            $teams_a_matches= $teams_human *($teams_human-1);
             
            //创建A组match
            for ($i = 1; $i <= $teams_a_matches; $i++) {
                $match = new Match();
                $match->compare_id=$compare_id;
                $match->round = 1;  //小组赛 直接写死.
                $match->type ="小组赛";
                
            }
            //创建B组match
            for ($j = 1; $j <= $teams_a_matches; $j++){

            }
        }
    }
}
