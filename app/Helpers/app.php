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

        $piwik = new PiwikTracker($siteId, $matomo);
        // $piwik->setUserId(getUniqueUserId());
        return $piwik;
    }
    return null;
}

function app_track_event($category, $action, $name = false, $value = false)
{
    if ($tracker = init_piwik_tracker()) {
        try
        {
            $tracker->doTrackEvent($category, $action, $name, $value);
        } catch (\Exception $ex) {
            Log::debug("app_track_event:" . $ex->getMessage());
        }
    }
}

function app_track_goal($goal_id)
{
    if ($tracker = init_piwik_tracker()) {
        try
        {
            $tracker->doTrackGoal($goal_id);
        } catch (\Exception $ex) {
            Log::debug("app_track_goal:" . $ex->getMessage());
        }
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
    app_track_goal(1);
    app_track_user('visit');
}

function app_track_like()
{
    app_track_goal(2);
    app_track_user('like');
}

function app_track_post()
{
    app_track_goal(3);
    app_track_user('post');
}

function app_track_comment()
{
    app_track_goal(4);
    app_track_user('comment');
}

function app_track_send_message()
{
    app_track_goal(5);
    app_track_user('send_message');
}

function app_track_launch()
{
    app_track_goal(6);
    app_track_user('launch');
}

function app_track_app_download()
{
    app_track_goal(7);
    app_track_user('app_download');
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
    $token = !empty($request->header('token')) ? $request->header('token') : $request->get('token');
    if (!empty($token)) {
        $user = User::where('api_token', $token)->first();
        if ($user) {
            return $user->id;
        }
    }

    //web guest
    if ($session_id = session_id()) {
        return $session_id;
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
