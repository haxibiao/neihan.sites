<?php

namespace App\Http\Controllers;

use Haxibiao\Helpers\matomo\PiwikTracker;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['loginAs','robot']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        //不再单个网站统计编辑工作量和流量

        return view('home')->withUser($user);
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

        //记录到matomo
        if (isset(config('matomo.site')[env('APP_DOMAIN')])) {
            $siteId = config('matomo.site')[env('APP_DOMAIN')];
            $matomo = config('matomo.matomo');

            $piwik = new PiwikTracker($siteId, $matomo);
            $piwik->setUserId($user->id);
            $piwik->doTrackEvent('visit', 'login', 'userLogin');
        }

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

    public function robot(){
        $domain = get_domain();
        $robotContent = <<<EOD
User-agent: *
Disallow: 
Disallow: /*q=*
Sitemap: https://www.$domain/sitemap.xml
EOD;
        return response($robotContent)
            ->header('Content-Type', 'text/plain');
    }
}
