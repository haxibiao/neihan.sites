<?php

use App\Article;
use App\Exceptions\UnregisteredException;
use App\Helpers\matomo\PiwikTracker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

function init_piwik_tracker()
{
    if (isset(config('matomo.site')[env('APP_DOMAIN')])) {
        $siteId = config('matomo.site')[env('APP_DOMAIN')];
        $matomo = config('matomo.matomo');

        $tracker = new PiwikTracker($siteId, $matomo);

        $tracker->setUserId(getUniqueUserId());

        //如果是APP请求 gql api
        if (str_contains(request()->path(), 'graphql')) {
            //设备系统
            $tracker->setCustomTrackingParameter('dimension2', request()->header('os'));
            $store_domain = 'unknown';
            $referrer     = request()->header('referrer');
            if (!empty($referrer)) {
                $store_domain = parse_url($referrer, PHP_URL_HOST);
            }
            //安装来源
            $tracker->setCustomTrackingParameter('dimension4', $store_domain);
            //APP版本
            $tracker->setCustomTrackingParameter('dimension5', request()->header('version'));
            //APP build
            $tracker->setCustomTrackingParameter('dimension6', request()->header('build'));
        }

        return $tracker;
    }
    return null;
}

function app_track_event($category, $action, $name = false, $value = false)
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackEvent($category, $action, $name, $value);
    }
}

function app_track_user($action, $name = false, $value = false)
{
    app_track_event("user", $action, $name, $value);
}

function app_track_video($action, $name = false, $value = false)
{
    app_track_event("video", $action, $name, $value);
}

function app_track_visit_people()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(1);
        app_track_user('visit_people');
    }
}

function app_track_like()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(2);
        app_track_user('like');
    }
}

function app_track_post()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(3);
        app_track_user('post');
    }
}

function app_track_comment()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(4);
        app_track_user('comment');
    }
}

function app_track_send_message()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(5);
        app_track_user('send_message');
    }
}

function app_track_launch()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(6);
        app_track_user('launch');
    }
}

function app_track_app_download()
{
    if ($tracker = init_piwik_tracker()) {
        $tracker->doTrackGoal(7);
        app_track_user('app_download');
    }
}

/**
 * 首页的文章列表
 * @return collection([article]) 包含分页信息和移动ＶＵＥ等优化的文章列表
 */
function indexArticles()
{
    $qb = Article::from('articles')
        ->with('user')->with('category')
        ->exclude(['body', 'json'])
        ->where('status', '>', 0)
        ->whereNull('source_url')
        ->whereNotNull('category_id')
        ->orderBy('updated_at', 'desc');
    $total    = count($qb->get());
    $articles = $qb->offset((request('page', 1) * 10) - 10)
        ->take(10)
        ->get();

    //过滤置顶的文章
    $stick_article_ids = array_column(get_stick_articles('发现'), 'id');
    $filtered_articles = $articles->filter(function ($article, $key) use ($stick_article_ids) {
        return !in_array($article->id, $stick_article_ids);
    })->all();

    $articles = [];
    foreach ($filtered_articles as $article) {
        $articles[] = $article;
    }

    //移动端，用简单的分页样式
    if (\Agent::isMobile()) {
        $articles = new Paginator($articles, 10);
        $articles->hasMorePagesWhen($total > request('page') * 10);
    } else {
        $articles = new LengthAwarePaginator($articles, $total, 10);
    }
    return $articles;
}

function getUniqueUserId()
{
    //web
    if (Auth::id()) {
        return Auth::id();
    }
    //rest api
    if (request()->user()) {
        return request()->user()->id;
    }
    //gql api
    $token = !empty(request()->header('token')) ? request()->header('token') : request()->get('token');
    if (!empty($token)) {
        $user = \App\User::where('api_token', $token)->first();
        if ($user) {
            return $user->id;
        }
    }

    //web guest
    if (!str_contains(request()->path(), 'graphql')) {
        if ($session_id = session_id()) {
            return $session_id;
        }
    }

    //app guest
    return getIp();
}

function getIp()
{
    return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}

function getUserId()
{
    if (Auth::id()) {
        return Auth::id();
    }
    if (request()->user()) {
        return request()->user()->id;
    }
    return 0;
}

function checkUser()
{
    return Auth::check() || session('user') || request()->user();
}

function getUser()
{
    if (Auth::check()) {
        return Auth::user();
    }

    $user = session('user');

    if (!$user) {
        throw new UnregisteredException('客户端还没登录...');
    }
    return $user;
}

function ajaxOrDebug()
{
    return request()->ajax() || request('debug');
}
