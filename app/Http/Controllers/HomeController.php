<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        $user              = Auth::user();
        $data['traffic']   = [];
        $labels['traffic'] = [];
        $traffic_count     = DB::table('traffic')->select(DB::raw('count(*) as count'), 'date')
            ->where('created_at', '>', \Carbon\Carbon::now()->subDay(7))
            ->where('user_id', Auth::user()->id)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        foreach ($traffic_count as $key => $value) {
            $labels['traffic'][] = str_replace(\Carbon\Carbon::now()->year . '-', '', $key);
            $data['traffic'][]   = $value;
        }
        $data['article']   = [];
        $labels['article'] = [];
        $articles_count    = DB::table('articles')->select(DB::raw('count(*) as count'), 'date')
            ->where('created_at', '>', \Carbon\Carbon::now()->subDay(9))
            ->where('user_id', Auth::user()->id)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        foreach ($articles_count as $key => $value) {
            $labels['article'][] = str_replace(\Carbon\Carbon::now()->year . '-', '', $key);
            $data['article'][]   = $value;
        }

        //compare all editors in one site ..
        $data['traffic_editors']   = [];
        $lables['traffic_editors'] = [];
        $editors                   = User::where('is_editor', 1)->pluck('name', 'id')->toArray();
        foreach ($editors as $id => $editor) {
            $editors_ids[] = $id;
        }
        $traffic_editors = DB::table('traffic')->select(DB::raw('count(*) as count, user_id'))
            ->where('date', \Carbon\Carbon::now()->subDay(1)->toDateString())
            ->whereIn('user_id', $editors_ids)
            ->groupBy('user_id')
            ->pluck('count', 'user_id');
        foreach ($traffic_editors as $user_id => $count) {
            $labels['traffic_editors'][] = $editors[$user_id];
            $data['traffic_editors'][]   = $count;
        }
        $traffic_editors = DB::table('articles')->select(DB::raw('count(*) as count, user_id'))
            ->where('date', \Carbon\Carbon::now()->subDay(1)->toDateString())
            ->whereIn('user_id', $editors_ids)
            ->groupBy('user_id')
            ->pluck('count', 'user_id');
        $labels['article_editors'] = [];
        $data['article_editors']   = [];
        foreach ($traffic_editors as $user_id => $count) {
            $labels['article_editors'][] = $editors[$user_id];
            $data['article_editors'][]   = $count;
        }

        return view('home')->withUser($user)->withData($data)->withLabels($labels);
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

    public function hxbLoginAs(Request $request, $name)
    {
        //time() 太快了，用date 吧
        if ($request->get('sign') !== bcrypt('hxb_'.\Carbon\Carbon::now()->toDateString())) {
            return '登录操作非法';
        }
        $user = User::where('name', $name)->firstOrFail();
        Auth::login($user);
        return redirect()->to('/home');
    }
}
