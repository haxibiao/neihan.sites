<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users         = User::orderBy('id', 'desc')->take(10)->get();
        $user          = Auth::user();
        $data['traffic'] = [];
        $labels['traffic'] = [];
        $traffic_count = DB::table('traffic')->select(DB::raw('count(*) as count'), 'date')
            ->where('created_at', '>', \Carbon\Carbon::now()->subDay(7))
            ->where('user_id', Auth::user()->id)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        foreach ($traffic_count as $key => $value) {
            $labels['traffic'][] = str_replace(\Carbon\Carbon::now()->year . '-', '', $key);
            $data['traffic'][]   = $value;
        }
        $data['article'] = [];
        $labels['article'] = [];
        $articles_count = DB::table('articles')->select(DB::raw('count(*) as count'), 'date')
            ->where('created_at', '>', \Carbon\Carbon::now()->subDay(9))
            ->where('user_id', Auth::user()->id)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        foreach ($articles_count as $key => $value) {
            $labels['article'][] = str_replace(\Carbon\Carbon::now()->year . '-', '', $key);
            $data['article'][]   = $value;
        }

        return view('home')->withUsers($users)->withUser($user)->withData($data)->withLabels($labels);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile')->withUser($user);
    }

    public function loginAs($id)
    {
        if (!Auth::user()->is_admin) {
            return '需要管理权限';
        }
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect()->to('/home');
    }
}
