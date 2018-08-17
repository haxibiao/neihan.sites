<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
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
        $this->middleware('auth', ['except' => ['loginAs']]);
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

        $data['traffic_wx']   = [];
        $labels['traffic_wx'] = [];
        $traffic_count        = DB::table('traffic')->select(DB::raw('count(*) as count'), 'date')
            ->where('created_at', '>', \Carbon\Carbon::now()->subDay(7))
            ->where('user_id', Auth::user()->id)
            ->where('is_wechat', 1)
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        foreach ($traffic_count as $key => $value) {
            $labels['traffic_wx'][] = str_replace(\Carbon\Carbon::now()->year . '-', '', $key);
            $data['traffic_wx'][]   = $value;
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

        //compare all editors work of yesterday in one site ..
        $editors_ids = [];
        $editors     = User::where('is_editor', 1)->pluck('name', 'id')->toArray();
        foreach ($editors as $id => $editor) {
            $editors_ids[] = $id;
        }

        $traffic_editors = DB::table('traffic')->select(DB::raw('count(*) as count, user_id'))
            ->where('date', \Carbon\Carbon::now()->subDay(1)->toDateString())
            ->whereIn('user_id', $editors_ids)
            ->groupBy('user_id')
            ->pluck('count', 'user_id');
        $wxtraffic_editors = DB::table('traffic')->select(DB::raw('count(*) as count, user_id'))
            ->where('date', \Carbon\Carbon::now()->subDay(1)->toDateString())
            ->where('is_wechat', 1)
            ->whereIn('user_id', $editors_ids)
            ->groupBy('user_id')
            ->pluck('count', 'user_id');

        $data['traffic_editors']   = [];
        $data['wxtraffic_editors'] = [];
        $labels['traffic_editors'] = [];
        foreach ($traffic_editors as $user_id => $count) {
            $labels['traffic_editors'][] = $editors[$user_id];
            $data['traffic_editors'][]   = $count;
        }
        foreach ($wxtraffic_editors as $user_id => $count) {
            $data['wxtraffic_editors'][] = $count;
        }

        $article_editors = DB::table('articles')->select(DB::raw('count(*) as count, user_id'))
            ->where('date', \Carbon\Carbon::now()->subDay(1)->toDateString())
            ->whereIn('user_id', $editors_ids)
            ->groupBy('user_id')
            ->pluck('count', 'user_id');

        $data['article_editors']   = [];
        $labels['article_editors'] = [];
        foreach ($article_editors as $user_id => $count) {
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
        $user = User::findOrFail($id);
        Auth::login($user);
        Cookie::queue('graphql_user', $user->id, 60 * 24); //存一天cookie 给graphiql 测试用
        return redirect()->to('/user/' . $id);
    }

    public function hxbLoginAs(Request $request, $name)
    {
        //time() 太快了，用date 吧, bcrypt 加密太牛了，时间，未知不同，出来结果就不同！！！，　用简单md5 验证sign

        if ($request->get('sign') !== md5('hxb_' . \Carbon\Carbon::now()->toDateString())) {
            return '登录操作非法';
        }
        $user = User::where('name', $name)->firstOrFail();
        Auth::login($user);
        return redirect()->to('/home');
    }
}
