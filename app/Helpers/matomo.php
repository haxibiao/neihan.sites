<?php

function init_piwik_tracker()
{

    if (isset(config('matomo.site')[env('APP_DOMAIN')])) {
        $siteId = config('matomo.site')[env('APP_DOMAIN')];
        $matomo = config('matomo.matomo');
        // $siteId = env('MATOMO_SITE_ID');
        // $matomo = env('MATOMO_URL');

        $piwik = new PiwikTracker($siteId, $matomo);
        $piwik->setUserId(getUniqueUserId());
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
