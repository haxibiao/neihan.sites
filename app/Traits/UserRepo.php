<?php

namespace App\Traits;

use App\Exchange;
use App\Profile;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait UserRepo
{
    public function isExchangeToday()
    {
        $exchange = Exchange::where('user_id', $this->id)->latest('id')->first();
        if (is_null($exchange) || now()->diffInDays($exchange->created_at) > 0) {
            return false;
        }

        return true;
    }
    public function createUser($name, $account, $password)
    {
        $user           = new User();
        $user->account  = $account;
        $user->phone    = $account;
        $user->name     = $name;
        $user->password = bcrypt($password);

        $user->avatar    = User::AVATAR_DEFAULT;
        $user->api_token = str_random(60);

        $user->save();

        Profile::create([
            'user_id' => $user->id,
        ]);

        return $user;
    }

    public function canJoinChat($chatId)
    {
        $chats = $this->chats()->where('chat_id', $chatId)->first();
        if (!is_null($chats)) {
            return true;
        }
        return false;
    }

    //重写用户的重置密码邮件通知
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * 保存用户头像
     * @return [type] [description]
     */
    public function save_avatar($file)
    {
        //判断是否为空
        if (empty($file) || !$file->isValid()) {
            return null;
        }
        // 获取文档相关信息
        $extension = $file->getClientOriginalExtension();
        $realPath  = $file->getRealPath(); //临时文档的绝对路径
        $filename  = 'avatar/' . $this->id . '_' . time() . '.' . $extension;

        try {
            Storage::cloud()->put($filename, file_get_contents($realPath));
            //上传到COS
            $url          = Storage::cloud()->url($filename);
            $this->avatar = $url;
            $this->save();
        } catch (\Exception $e) {
            //FIXME:上传COS失败？？
        }

        return $this->avatar;
    }

    public function save_background($file)
    {
        //判断是否为空
        if (empty($file) || !$file->isValid()) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();
        $realPath  = $file->getRealPath(); //临时文档的绝对路径

        $filename = 'background/' . $this->id . '_' . time() . '.' . $extension;
        Storage::cloud()->put($filename, file_get_contents($realPath));
        //上传到COS失败
        $bgurl = Storage::cloud()->url($filename);

        //save profile
        $profile             = $this->profile;
        $profile->background = $bgurl;
        $profile->save();

        return $bgurl;
    }

    public function fillForJs()
    {
        $this->introduction = $this->introduction;
        $this->avatar       = $this->avatarUrl;
    }

    public function blockedUsers()
    {
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }

        $blocked = [];
        if (isset($json['blocked'])) {
            $blocked = $json['blocked'];
        }
        return $blocked;
    }

    public function blockUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }

        $blocked = [];
        if (isset($json['blocked']) && is_array($json['blocked'])) {
            $blocked = $json['blocked'];
        }

        $blocked = new \Illuminate\Support\Collection($blocked);

        if ($blocked->contains('id', $user_id)) {
            //unbloock
            $blocked = $blocked->filter(function ($value, $key) use ($user_id) {
                return $value['id'] != $user_id;
            });
        } else {
            $blocked[] = [
                'id'     => $user->id,
                'name'   => $user->name,
                'avatar' => $user->avatarUrl,
            ];
        }

        $json['blocked'] = $blocked;
        $this->json      = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    public function report($type, $reason, $comment_id = null)
    {
        $this->count_reports = $this->count_reports + 1;

        $json = json_decode($this->json);
        if (!$json) {
            $json = (object) [];
        }

        $user    = getUser();
        $reports = [];
        if (isset($json->reports)) {
            $reports = $json->reports;
        }

        $report_data = [
            'type'   => $type,
            'reason' => $reason,
        ];
        if ($comment_id) {
            $report_data['comment_id'] = $comment_id;
        }
        $reports[] = [
            $user->id => $report_data,
        ];

        $json->reports = $reports;
        $this->json    = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    public function reports()
    {
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }
        $reports = [];
        if (isset($json['reports'])) {
            $reports = $json['reports'];
        }
        return $reports;
    }

    public function transfer($amount, $to_user, $log_mine = '转账', $log_theirs = '转账', $relate_id = null, $type = "打赏")
    {
        if ($this->balance < $amount) {
            return false;
        }

        DB::beginTransaction();

        try {
            \App\Transaction::create([
                'user_id'      => $this->id,
                'relate_id'    => $relate_id,
                'from_user_id' => $this->id,
                'to_user_id'   => $to_user->id,
                'type'         => $type,
                'log'          => $log_mine,
                'amount'       => $amount,
                'status'       => '已到账',
                'balance'      => $this->balance - $amount,
            ]);
            \App\Transaction::create([
                'user_id'      => $to_user->id,
                'relate_id'    => $relate_id,
                'from_user_id' => $this->id,
                'to_user_id'   => $to_user->id,
                'type'         => $type,
                'log'          => $log_theirs,
                'amount'       => $amount,
                'status'       => '已到账',
                'balance'      => $to_user->balance + $amount,
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    public function makeQQAvatar()
    {
        //尝试读取qq头像
        if (empty($this->email)) {
            $qq = $this->qq;
            if (empty($qq)) {
                $pattern = '/(\d+)\@qq\.com/';
                if (preg_match($pattern, strtolower($this->email), $matches)) {
                    $qq = $matches[1];
                }
            }
            if (!empty($qq)) {
                $avatar_path = '/storage/avatar/' . $this->id . '.qq.jpg';
                $qq_pic      = get_qq_pic($qq);
                $qq_img_data = @file_get_contents($qq_pic);
                if ($qq_img_data) {
                    file_put_contents(public_path($avatar_path), $qq_img_data);
                    $hash = md5_file(public_path($avatar_path));
                    if ($hash != md5_file(public_path('/images/qq_default.png')) && $hash != md5_file(public_path('/images/qq_tim_default.png'))) {
                        $this->avatar = $avatar_path;
                    }
                }
            }
        }
        $this->save();

        return $this->avatar;
    }

    public function unreads($type = null, $num = null)
    {
        //缓存未命中
        $unreadNotifications = \App\Notification::where([
            'read_at'       => null,
            'notifiable_id' => $this->id,
        ])->get();
        $unreads = [
            'comments' => null,
            'likes'    => null,
            'follows'  => null,
            'tips'     => null,
            'others'   => null,
            'chat'     => null,
        ];
        //下列通知类型是进入了notification表的
        $unreadNotifications->each(function ($item) use (&$unreads) {
            switch ($item->type) {
                //评论文章通知
                case 'App\Notifications\ArticleCommented':
                    $unreads['comments']++;
                    break;
                case 'App\Notifications\ReplyComment':
                    $unreads['comments']++;
                    break;
                //喜欢文章通知
                case 'App\Notifications\ArticleLiked':
                    $unreads['likes']++;
                    break;
                //关注用户通知
                case 'App\Notifications\UserFollowed':
                    $unreads['follows']++;
                    break;
                //打赏文章通知
                case 'App\Notifications\ArticleTiped':
                    $unreads['tips']++;
                    break;
                //打赏文章通知
                case 'App\Notifications\ChatNewMessage':
                    $unreads['chat']++;
                    break;
                //其他类型的通知
                default:
                    $unreads['others'];
                    break;
            }
        });

        //聊天消息数
        $unreads['chats'] = $this->chats->sum(function ($item) {
            return $item->pivot->unreads;
        });
        //投稿请求数
        $unreads['requests'] = $this->adminCategories()->sum('new_requests');

        //write cache
        Cache::put('unreads_' . $this->id, $unreads, 60);

        if ($num) {
            $unreads[$type] = $num;
            //write cache
            Cache::put('unreads_' . $this->id, $unreads, 60);
        }
        if ($type) {
            return $unreads[$type] ? $unreads[$type] : null;
        }

        return $unreads;
    }

    public function forgetUnreads()
    {
        Cache::forget('unreads_' . $this->id);
    }
}
