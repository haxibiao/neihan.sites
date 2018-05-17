<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;
use Illuminate\Http\Request;
use Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        return view('admin.index');
    }

    public function users()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);
        return view('admin.users')->withUsers($users);
    }

    public function articleSticks()
    {
        $articles = get_stick_articles('', true);
        return view('admin.stick_articles')->withArticles($articles);
    }

    public function articleStick()
    {
        $data  = request()->all();
        stick_article($data);
        return redirect()->back();
    }

    public function deleteStickArticle()
    {
        $article_id = request()->get('article_id');
        $items      = [];
        if (Storage::exists("stick_articles")) {
            $json  = Storage::get('stick_articles');
            $items = json_decode($json, true);
        }
        $left_items = [];
        foreach ($items as $item) {
            if ($item['article_id'] != $article_id) {
                $left_items[] = $item;
            }
        }
        $json = json_encode($left_items);
        Storage::put("stick_articles", $json);
        return redirect()->back();
    }

    public function seoConfig()
    {
        $config           = (object) [];
        $config->seo_meta = '';
        $config->seo_push = '';
        $config->seo_tj   = '';
        if (Storage::exists("seo_config")) {
            $json   = Storage::get('seo_config');
            $config = json_decode($json);
        }
        return view('admin.seo_config')->withConfig($config);
    }

    public function saveSeoConfig()
    {
        $config = request()->all();
        $json   = json_encode($config);
        Storage::put("seo_config", $json);
        return redirect()->back()->with('saved', true);
    }

    public function friendLinks()
    {
        $links = [];
        if (Storage::exists("friend_links")) {
            $json  = Storage::get('friend_links');
            $links = json_decode($json, true);
        }
        return view('admin.friend_links')->withLinks($links);
    }

    public function addFriendLink()
    {
        $newLinkData = request()->all();
        $links       = [];
        if (Storage::exists("friend_links")) {
            $json  = Storage::get('friend_links');
            $links = json_decode($json, true);
        }
        $links[] = $newLinkData;
        $json    = json_encode($links);
        Storage::put("friend_links", $json);
        return redirect()->back();
    }

    public function deleteFriendLink()
    {
        $deleteDomain = request()->get('website_domain');
        $links        = [];
        if (Storage::exists("friend_links")) {
            $json  = Storage::get('friend_links');
            $links = json_decode($json, true);
        }
        $left_links = [];
        foreach ($links as $link) {
            if ($link['website_domain'] != $deleteDomain) {
                $left_links[] = $link;
            }
        }
        $json = json_encode($left_links);
        Storage::put("friend_links", $json);
        return redirect()->back();
    }

    public function article_push()
    {
        return view('admin.article_push');
    }

    public function push_article(Request $request)
    {
        $urls   = [];
        $number = $request->number;
        $type   = $request->type;

        switch ($type) {
            case 'pandaNumber':
                $appid = config('seo.articlePush.pandaNumber.appid');
                $token = config('seo.articlePush.pandaNumber.token');
                $api   = 'http://data.zz.baidu.com/urls?appid=' . $appid . '&token=' . $token . '&type=realtime';
                break;
            case 'baiduNumber':
                $token = config('seo.articlePush.baiduNumber.token');
                $api   = 'http://data.zz.baidu.com/urls?site=' . env('APP_URL') . '&token=' . $token;
                break;
            default:
                dd('提交的类型错误 没有这个类型');
                break;
        }

        $articles = Article::orderBy('id', 'desc')
            ->where('status', '>', 0)->take($number)->get();
        foreach ($articles as $article) {
            $urls[] = config('app.url') . '/article/' . $article->id;
        }

        $ch      = curl_init();
        $options = array(
            CURLOPT_URL            => $api,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => implode("\n", $urls),
            CURLOPT_HTTPHEADER     => array('Content-Type: text/plain'),
        );

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }
}
