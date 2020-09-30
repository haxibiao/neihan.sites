<?php

namespace App\Traits;

use App\CheckIn;
use App\Contribute;
use App\Dimension;
use App\Exceptions\GQLException;
use App\Exceptions\UserException;
use App\Exchange;
use App\Gold;
use App\OAuth;
use App\Profile;
use App\Transaction;
use App\User;
use App\Verify;
use App\Wallet;
use App\Withdraw;
use Haxibiao\Base\Exceptions\SignInException;
use Haxibiao\Helpers\PhoneUtils;
use Haxibiao\Helpers\WechatUtils;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UserRepo
{
    //标记上次提现提交的时间
    public function withdrawAt()
    {
        $this->withdraw_at = now();
        $this->save();
    }

    public function startExchageChangeToWallet()
    {
        //账户异常
        if ($this->isShuaZi) {
            return;
        }
        //注意:此处默认为双精度 根据默认10000:1的兑换率  50智慧点上下浮动兑换会出现差距0.01
        $wallet      = $this->wallet;
        $amount      = Exchange::computeAmount($this->gold);
        $amount      = floor($amount * 100) / 100;
        $gold        = $amount * Exchange::RATE;
        $goldBalance = $this->gold - $gold;

        //兑换条件:金币余额 >= 0 && 兑换金额 > 0 && 钱包已存在
        $canExchange = $goldBalance >= 0 && $gold > 0 && !is_null($wallet);
        if (!$canExchange) {
            return null;
        }

        /**
         * 开启事务、锁住智慧点记录
         */
        DB::beginTransaction();
        //兑换状态
        try {
            //扣除智慧点
            Gold::makeOutcome($this, $gold, "兑换余额");
            //添加兑换记录
            Exchange::exchangeOut($this, $gold);
            //添加流水记录
            Transaction::makeIncome($wallet, $amount, '智慧点兑换');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack(); //数据库回滚
            \Yansongda\Supports\Log::error($ex);
        }
    }

    /**
     * 成功提现总金额数
     *
     * @return int
     */
    public function getSuccessWithdrawAmountAttribute()
    {
        return $this->withdraws()
            ->where('withdraws.status', '>', 0)
            ->sum('withdraws.amount');
    }


    public static function userReward(User $user, array $reward)
    {
        $action = Arr::get($reward, 'action');
        $result = [
            'gold'       => 0,
            'ticket'     => 0,
            'contribute' => 0,
        ];

        //记录该维度数据给运营看
        if (in_array($action, ['WATCH_REWARD_VIDEO', 'CLICK_REWARD_VIDEO', 'VIDEO_PLAY_REWARD'])) {
//            if ($action == 'VIDEO_PLAY_REWARD') {
//                Dimension::setDimension($user, $action, $reward['gold']);
//            } else {
//                Dimension::setDimension($user, $action, $reward['contribute']);
//            }
        }

        //智慧点奖励
        if (isset($reward['gold'])) {
            Gold::makeIncome($user, $reward['gold'], $reward['remark']);
            $result['gold'] = $reward['gold'];
        }

        //精力点奖励
        if (isset($reward['ticket'])) {
            if ($action != 'SUCCESS_ANSWER_VIDEO_REWARD' && $action != 'FAIL_ANSWER_VIDEO_REWARD') {
                $user->increment('ticket', $reward['ticket']);
                $result['ticket'] = $reward['ticket'];
            }
        }

        //贡献奖励
        if (isset($reward['contribute'])) {
            Contribute::rewardUserAction($user, $reward['contribute']);
            $result['contribute'] = $reward['contribute'];
        }
        //统计激励视频当天
        $profile = $user->profile;
        if ($action == 'WATCH_REWARD_VIDEO') {
            if ($profile->last_reward_video_time<today()){
                $profile->today_reward_video_count=1;
                $profile->last_reward_video_time = now();
                $profile->save();
            }else{
                $profile->increment('today_reward_video_count');
                $profile->last_reward_video_time = now();
                $profile->save();
            }
            //触发分享任务
            $user->reviewTasksByClass('Contribute');
        }

        //签到额外奖励
        if ($action == 'SIGNIN_VIDEO_REWARD') {
            $signRewards = CheckIn::getSignInReward($profile->keep_checkin_days);
            //智慧点
            if (isset($signRewards['gold_reward'])) {
                Gold::makeIncome($user, $signRewards['gold_reward'], '签到额外奖励');
                $result['gold'] = $signRewards['gold_reward'];
            }
            //贡献
            if (Arr::get($signRewards, 'contribute_reward', 0) > 0) {
                Contribute::rewardSignInAdditional($user, $signRewards['contribute_reward']);
                $result['contribute'] = $signRewards['contribute_reward'];
            }
        }

        //签到双倍奖励
        if ($action == 'DOUBLE_SIGNIN_REWARD') {
            $rewardRate = 2;
            $signIn     = CheckIn::todaySigned($user->id);
            throw_if(is_null($signIn), UserException::class, '领取失败,请先完成签到!');
            throw_if($signIn->reward_rate >= $rewardRate, UserException::class, '领取失败,签到双倍奖励已领取过!');

            //更新奖励倍率
            $signIn->reward_rate = $rewardRate;
            $signIn->save();
            $result = [
                'gold'       => $signIn->gold_reward,
                'contribute' => $signIn->contribute_reward,
            ];

            //双倍智慧
            if ($signIn->gold_reward > 0) {
                Gold::makeIncome($user, $result['gold'], '签到翻倍奖励');
            }

            //双倍贡献
            if ($signIn->contribute_reward > 0) {
                Contribute::rewardSignInDoubleReward($user, $signIn, $result['contribute']);
            }
        }


        return $result;
    }

    public function getLatestWatchRewardVideoTime()
    {
        return Dimension::where('user_id', $this->id)
            ->whereIn('name', ['WATCH_REWARD_VIDEO', 'CLICK_REWARD_VIDEO'])
            ->max('updated_at');
    }
    public  function smsSignIn($sms_code, $phone){

        throw_if(!is_phone_number($phone), SignInException::class, '手机号格式不正确!');
        throw_if(empty($sms_code), SignInException::class, '验证码不能为空!');

        $qb = User::wherePhone($phone);
        Verify::checkSMSCode($sms_code,$phone,Verify::USER_LOGIN);
        if ($qb->exists()){
            return $qb->get()->first();
        } else{
            //新用户注册账号
            $user = User::getDefaultUser();
            $user->phone=$phone;
            $user->account=$phone;
            $user->save();
            return $user;
        }
    }

    public  function authSignIn($code, $type){

        throw_if(!method_exists(self::class, $type), GQLException::class, '暂时只支持手机号和微信一键登录');
        $user = self::$type($code);
        return $user;
    }
    //手机号一键登录
    public function mobile($code){
        $accessTokens = PhoneUtils::getInstance()->accessToken($code);

        Log::info('移动获取号码接口参数',$accessTokens);

        $token = $accessTokens['msisdn'];
        if ($accessTokens['resultCode']!='103000' || !array_key_exists('msisdn', $accessTokens) ) {
            throw new GQLException("获取手机号一键登录授权失败");
        }

        $oAuth  = OAuth::firstOrNew(['oauth_type' => 'phone', 'oauth_id' => $token]);
        //已授权的老用户
        if (isset($oAuth->id)) {
            return $oAuth->user;
        }
        $user = User::firstOrNew([
            'phone'      => $token,
        ]);
        //初次授权的新用户
        if (!isset($user->id)) {
            $suffix = strval(time());
            $user->name="手机用户".$suffix;
            $user->api_token=str_random(60);
            $user->avatar=User::AVATAR_DEFAULT;
            $user->phone=$token;
            $user->account=$token;
            $user->save();
        }
        //初次授权的老用户
        $oAuth->user_id=$user->id;
        $oAuth->save();
        return $user;
    }

    //微信号一键登录
    public function wechat($code){
        try {
            $accessTokens = WechatUtils::getInstance()->accessToken($code);
            Log::info("微信用户登录接口回参",$accessTokens);
            if (!is_array($accessTokens) || !array_key_exists('unionid', $accessTokens) || !array_key_exists('openid', $accessTokens)) {
                throw new GQLException("获取微信登录授权失败");
            }
            $token =$accessTokens['unionid'];

            $oAuth  = OAuth::firstOrNew(['oauth_type' => 'wechat', 'oauth_id' => $token]);
            //已授权的老用户
            if (isset($oAuth->id)) {
                return $oAuth->user;
            }
            //初次授权的新用户
            $oAuth->data    = Arr::only($accessTokens, ['openid', 'refresh_token']);
            $oAuth->save();
            $user = $this->getDefaultUser();
            $wallet          = Wallet::firstOrNew(['user_id' => $user->id]);
            $wallet->wechat_account = $token;
            $wallet->save();
            //绑定微信信息
            $wechatUserInfo = WechatUtils::getInstance()->userInfo($accessTokens['access_token'], $accessTokens['openid']);
            Log::info("微信用户信息接口回参",$wechatUserInfo);
            if ($wechatUserInfo && Str::contains($user->name, User::DEFAULT_NAME)) {
                WechatUtils::getInstance()->syncWeChatInfo($wechatUserInfo, $user);
                Log::info("oauth",$oAuth->data);
                $wechatData = array_merge($oAuth->data, $wechatUserInfo);
                $oAuth->data = $wechatData;
                $oAuth->save();
            }
            $oAuth->user_id=$user->id;
            $oAuth->save();
            return $user;
        }catch (\Exception $e) {
            Log::info('异常信息'.$e->getMessage());
        }

    }

    //创建默认用户
    public static function getDefaultUser(){
        return User::firstOrCreate([
            'name'      => User::DEFAULT_NAME,
            'api_token' => str_random(60),
            'avatar'    => User::AVATAR_DEFAULT,
        ]);
    }

    public function followedUserIds($userIds)
    {
        return $this->follows()->select('followed_id')
            ->whereIn('followed_type', $userIds)
            ->where('followed_type', 'users')
            ->get()
            ->pluck('followed_id');
    }

    /**
     *
     * 计算高额提现倍率
     *
     * @param User $user
     * @return int
     */
    public function countByHighWithdrawCardsRate()
    {
        return 0;
    }

    /**
     *
     * 获取高额提现令牌数
     *
     * @param int $amount
     * @return int
     */
    public function countByHighWithdrawBadgeCount(int $amount)
    {
        return 0;
    }

    public function createUser($name, $account, $password)
    {
        $user = new User();

        if (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $user->email = $account;
        }
        if (is_phone_number($account)) {
            $user->phone = $account;
        }
        $user->account  = $account;
        $user->name     = $name;
        $user->password = bcrypt($password);

        $user->avatar    = User::AVATAR_DEFAULT;
        $user->api_token = str_random(60);

        $user->save();

        Profile::create([
            'user_id'     => $user->id,
            'app_version' => request()->header('version', null),
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

        return $this->avatar_url;
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
                case 'App\Notifications\CommentedNotification':
                    $unreads['comments']++;
                    break;
                case 'App\Notifications\ReplyComment':
                    $unreads['comments']++;
                    break;
                //喜欢文章通知
                case 'App\Notifications\LikedNotification':
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

    public function bannedAccount()
    {
        $this->status = self::STATUS_OFFLINE;
        $this->save();
    }

    public function isAdmin()
    {
        // if ($this->is_admin || $this->hasVerifiedEmail()) {
        //     return true;
        // }

        if ($this->is_admin || ends_with($this->email, '@haxibiao.com') || ends_with($this->account, '@haxibiao.com')) {
            return true;
        }
        return false;
    }

    public function isWithdrawBefore(): bool
    {
        $wallet_id = $this->wallet->id;
        return Withdraw::where('wallet_id', $wallet_id)->exists();
    }

    public function hasWithdrawOnDDZ(): bool
    {
        if ($ddzUser = $this->getDDZUser()) {
            $wallet = $ddzUser->getWalletAttribute();

            if ($wallet->is_withdraw_before !== null) {
                return $wallet->is_withdraw_before;
            }

            if ($this->getWalletAttribute()->withdraws()->exists()) {
                // fix data.. 工厂内提现过直接更新懂得赚账号是否提现标识
                $ddzUser->getWalletAttribute()->update(['is_withdraw_before' => true]);
            }

        }
        return false;
    }

    public function checkWithdraw($amount)
    {
        $contribute      = $this->profile->count_contributes;
        $need_contribute = $amount * 10;
        if ($contribute >= $need_contribute) {
            return true;
        }

        return false;
    }

    /**
     * 消耗贡献值提现
     * @return void
     * @author zengdawei
     */
    public function consumeContributeToWithdraw($amount, $type, $id)
    {
        Contribute::create([
            'user_id'          => $this->id,
            'amount'           => -($amount * Contribute::WITHDRAW_DATE),
            'remark'           => '提现兑换',
            'contributed_id'   => $id,
            'contributed_type' => $type,
        ]);
        Profile::where('user_id', $this->id)->decrement('count_contributes', $amount * Contribute::WITHDRAW_DATE);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }

    public function hasWithdrawToday(): bool
    {
        return $this->wallet->withdraws()
            ->whereDate('created_at', today())
            ->whereIn('status', [Withdraw::SUCCESS_WITHDRAW, Withdraw::WAITING_WITHDRAW])
            ->exists();
    }

    public function destoryUser()
    {
        $this->status = self::STATUS_DESTORY;
        $this->save();
    }

    //nova后台提现金额排行前十的用户
    public static function getTopWithDraw($number = 5)
    {
        $data          = [];
        $ten_top_users = \App\Wallet::select(\DB::raw('total_withdraw_amount,real_name,user_id'))
            ->where('type', 0)
            ->orderBy('total_withdraw_amount', 'desc')
            ->take($number)->get()->toArray();

        foreach ($ten_top_users as $top_user) {
            $user = User::find($top_user["user_id"]);
            //显示真实名字
            //$data['name'][] = $user ? $user->name : '空';
            $data['name'][] = $top_user["real_name"] ? $top_user["real_name"] : '空';
            $data['data'][] = $top_user["total_withdraw_amount"];
        }
        return $data;
    }

    public function isEditorRole()
    {
        return $this->role_id == self::EDITOR_STATUS;
    }

    public function isAdminRole()
    {
        return $this->role_id == self::ADMIN_STATUS;
    }

    public function isHighRole()
    {
        return $this->role_id >= self::EDITOR_STATUS;
    }

    public function getUserSuccessWithdrawAmount()
    {
        return $this->wallet->total_withdraw_amount;
    }

    //返回懂得赚账户
    public function getDDZUser()
    {
        return \DDZUser::getUser($this->uuid);
    }

    // FIXME: 绑定懂得赚 (基本废弃了...)
    public function bingDDZ(): void
    {
        $ddzUser = \DDZUser::getUser($this->uuid);
        OAuth::createRelation($this->id, 'dongdezhuan', $ddzUser->id);
    }
}
