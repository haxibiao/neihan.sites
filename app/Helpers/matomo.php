<?php

function init_piwik_tracker()
{
    $piwik = app('piwik');
    $piwik->setUserId(getUniqueUserId());
    return $piwik;
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
    app_track_event("用户行为", $action, $name, $value);
}

function app_track_post()
{
    app_track_user('发布动态');
}

function app_track_issue()
{
    app_track_user('发布视频问答','post');
}

function app_track_comment()
{
    app_track_user('comment');
}

function app_track_send_message()
{
    app_track_user('send_message');
}

function app_track_launch()
{
    app_track_user('launch');
}

function app_track_app_download()
{
    app_track_user('App下载','app_download');
}
