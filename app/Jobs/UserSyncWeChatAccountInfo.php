<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class UserSyncWeChatAccountInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $userId;
    protected $wechatUserInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $wechatUserInfo)
    {
        $this->userId         = $userId;
        $this->wechatUserInfo = $wechatUserInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::findOrFail($this->userId);
        if ($user->name == User::DEFAULT_NAME) {
            $user->name = $this->wechatUserInfo['nickname'];
        }
        if ($user->avatar == User::AVATAR_DEFAULT) {
            $user->updateAvatar($this->wechatUserInfo['headimgurl']);
        }
        $user->save();
    }
}
