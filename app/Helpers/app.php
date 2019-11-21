<?php

use App\Article;
use App\Exceptions\GQLException;
use App\Exceptions\UnregisteredException;
use App\Helpers\matomo\PiwikTracker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

function seo_value($group, $name)
{
    return \App\Seo::getValue($group, $name);
}

function aso_value($group, $name)
{
    return \App\Aso::getValue($group, $name);
}

function qrcode_url()
{
    $appName = env('APP_NAME');
    $apkUrl  = "http://{$appName}-1251052432.cos.ap-shanghai.myqcloud.com/{$appName}-release.apk";

    $logo   = "logo/{$appName}.com.small.png";
    $qrcode = QrCode::format('png')->size(250)->encoding('UTF-8');

    if (file_exists(public_path($logo))) {
        $qrcode->merge(public_path($logo), .1, true);
    }

    $qrcode = $qrcode->generate($apkUrl);

    $path = base64_encode($qrcode);

    return $path;

}

function small_logo()
{
    $logo = \App\Aso::getValue('下载页', 'logo');
    
    
    if(empty($logo)){
        return '/logo/' . env('APP_DOMAIN') . '.small.png';
    }else{
        return $logo;
    }
    
}

function is_staging_env()
{
    return config('app.env') == 'staging';
}

function is_local_env()
{
    return config('app.env') == 'local';
}

function is_dev_env()
{
    return config('app.env') == 'dev';
}

function is_prod_env()
{
    $environment = ['prod', 'production', 'hotfix'];

    return in_array(config('app.env'), $environment);
}

function is_night()
{
    return date('H') >= 21 || date('H') <= 8;
}

function is_prod()
{
    return env('APP_ENV') == 'prod';
}

function formatBytes($byteSize)
{
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $byteSize >= 1024 && $i < 4; $i++) {
        $byteSize /= 1024;
    }

    return round($byteSize, 2) . $units[$i];
}

function cdnurl($path)
{
    if (!is_prod() && file_exists(public_path($path))) {
        return url($path);
    }
    $path = "/" . $path;
    $path = str_replace('//', '/', $path);
    return "http://" . env('COS_DOMAIN') . $path;
}

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

function app_track_issue()
{
    app_track_goal(3);
    app_track_user('issue');
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
    if (isMobile()) {
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
    if ($user = request()->user()) {
        return $user->id;
    }
    return 0;
}

function checkUser()
{
    try {
        $user = getUser();
    } catch (\Exception $ex) {
        return null;
    }

    return $user;
}

function getUser($throw = true)
{
    if (Auth::check()) {
        return Auth::user();
    }

    if (auth('api')->user()) {
        return auth('api')->user();
    }

    $user = session('user');
    if (!$user) {
        if ($user = request()->user()) {
            return $user;
        }
        //兼容新版的lighthouse
        $token = request()->header('api_token') ?: request()->bearerToken();
        if ($token) {
            return \App\User::where('api_token', $token)->first();
        }
    }

    if (!$user && $throw) {
        throw new UnregisteredException('客户端还没登录...');
    }
    return $user;
}

function ajaxOrDebug()
{
    return request()->ajax() || request('debug');
}
/**
 * 过滤多余文字，只留下 链接
 *
 * @param [type] $str
 * @return void
 * @author zengdawei
 */
function filterText($str)
{
    if (empty($str) || $str == '' || is_null($str)) {
        throw new GQLException('分享链接是空的，请检查是否有误噢');
    }

    $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';

    if (preg_match($regex, $str, $match)) {
        return $match;
    } else {
        throw new GQLException('分享链接失效了，请检查是否有误噢');
    }

}
