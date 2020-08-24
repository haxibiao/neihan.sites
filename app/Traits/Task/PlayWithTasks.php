<?php

namespace App\Traits;

use App\Task;

trait PlayWithTasks
{

    //新手任务
    public function getNewUserTasks()
    {
        return Task::whereType(0)->get();
    }

    //直播任务
    public function getUserLiveTasks()
    {
        return Task::whereName('直播任务')->get();
    }

    public function getLikeTasksAttribute()
    {
        //邀请类的任务
        return Task::whereName('作品获赞')->get();
    }

    public function getInvitationTasksAttribute()
    {
        //邀请类的任务
        return Task::whereName('邀请任务')->get();
    }

    public function getArticleTasksAttribute()
    {
        //目前只有一个发布类的任务
        return Task::whereName('视频发布满15个')->get();
    }
}
