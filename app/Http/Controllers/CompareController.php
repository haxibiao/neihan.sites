<?php

namespace App\Http\Controllers;

use App\Compare;
use App\Team;
use App\User;
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
            "4" => 4,
            "6" => 6,
            "8" => 8,
        ];
        if (AjaxOrDebug()) {
            $users = User::orderBy('id')->pluck('name', 'id');
            return $users;
        }
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

        //save this compare team

        // if (is_array($request->teams)) {
        //     foreach ($request->teams as $item) {
        //         $team             = new Team;
        //         $team->name       = $item;
        //         $team->compare_id = $compare->id;
        //         $team->save();
        //     }
        // }

        //save
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
}
