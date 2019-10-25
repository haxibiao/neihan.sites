<?php

namespace App\Listeners;

use App\Events\NewLike;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLikeNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * ivan(2019-10-15): 监听喜欢点赞操作的其他响应.
     * LikeObserver 应该已经完成了基本的数据更新触发
     * 这里可以用来实现一些复杂的 job逻辑，比如 延迟发送通知，聚合发送点赞通知 ...
     * @param  NewLike  $event
     * @return void
     */
    public function handle(NewLike $event)
    {
        //TODO: 简单统计更新可以转移到 observer ，监听这里用来汇总一些复杂逻辑的汇总事件通知算法

    }
}
