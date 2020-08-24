<?php

namespace App\Traits;

use App\BlackList;
use App\Contribute;
use App\Exchange;
use App\Follow;
use App\Profile;
use App\RewardCounter;
use App\User;
use App\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait UserAttrsCache
{
    //返回至少6位数字的邀请码
    public function getInviteCodeCache()
    {
        $code = $this->id;
        $minLength = 6;
        while (strlen($code) < $minLength) {
            $code = '0' . $code;
        }
        return $code;
    }

    //返回用户所有直播观众数量
    public function getCountAudiencesCache()
    {
        return $this->userLives()->sum('count_users');
    }

    public function getCountSuccessInvitationCache()
    {
        return $this->invitations()->whereNotNull('invited_in')->count();
    }

    /**
     *
     * 高额提现限量抢成功率 2倍
     *
     * @return int
     */
    public function getDoubleHighWithdrawCardsCountCache()
    {
        return 0;
        // return $this->countByHighWithdrawCardsRate();
    }
    /**
     *
     * 高额提现限量抢成功率 5倍
     *
     * @return int
     */
    public function getFiveTimesHighWithdrawCardsCountCache()
    {
        return 0;
        // return $this->countByHighWithdrawCardsRate();
    }

    /**
     *
     * 高额提现限量抢成功率 10倍
     *
     * @return int
     */
    public function getTenTimesHighWithdrawCardsCountCache()
    {
        return 0;
        // return $this->countByHighWithdrawCardsRate();
    }
    /**
     *
     * 高额提现令牌数  3元
     *
     * @return mixed
     */
    public function getThreeYuanWithdrawBadgesCountCache()
    {
        return 0;
        // return $this->countByHighWithdrawBadgeCount(3);
    }
    /**
     *
     * 高额提现令牌数  5元
     *
     * @return mixed
     */
    public function getFiveYuanWithdrawBadgesCountCache()
    {
        return 0;
        // return $this->countByHighWithdrawBadgeCount(5);
    }
    /**
     *
     * 高额提现令牌数  10元
     *
     * @return mixed
     */
    public function getTenYuanWithdrawBadgesCountCache()
    {
        return 0;
        // return $this->countByHighWithdrawBadgeCount(10);
    }

    //用户激励视频的计数器
    public function getRewardCounterCache()
    {
        $counter = $this->hasOne(RewardCounter::class)->first();
        if (!$counter) {
            $counter = RewardCounter::firstOrCreate([
                'user_id' => $this->id,
            ]);
        }
        return $counter;
    }

    public function getGoldCache()
    {
        return $this->goldWallet->goldBalance;
    }

    //rmb钱包，默认钱包
    public function getWalletCache()
    {
        if ($wallet = $this->wallets()->whereType(0)->first()) {
            return $wallet;
        }

        return Wallet::rmbWalletOf($this);
    }

    public function getIsExchangeTodayCache()
    {
        return $this->exchanges()->whereDate('created_at', \Carbon\Carbon::today())->count() > 0;
    }

    //兼容前端:下一版去掉
    public function getIsWalletCache()
    {
        $wallet = $this->wallets()->whereType(0)->first();

        return $wallet;
    }

    //金币钱包
    public function getGoldWalletCache()
    {
        if ($wallet = $this->wallets()->whereType(1)->first()) {
            return $wallet;
        }
        return Wallet::goldWalletOf($this);
    }

    //TODO 临时过渡
    public function getCashCache()
    {
        $tansaction = $this->transactions()
            ->latest()->first();
        if (!$tansaction) {
            return 0;
        }
        return $tansaction->balance;
    }

    public function checkAdmin()
    {
        return $this->is_admin;
    }

    public function checkEditor()
    {
        return $this->is_editor || $this->is_admin;
    }

    public function isFollow($type, $id)
    {
        return $this->followings()->where('followed_type', get_polymorph_types($type))->where('followed_id', $id)->count() ? true : false;
    }

    public function isLiked($type, $id)
    {
        return $this->likes()->where('liked_type', get_polymorph_types($type))->where('liked_id', $id)->count() ? true : false;
    }

    public function isBlack()
    {

        $black = BlackList::where('user_id', $this->id);
        $is_black = $black->exists();
        return $is_black;
    }

    public function link()
    {
        return '<a href="/user/' . $this->id . '">' . $this->name . '</a>';
    }

    public function at_link()
    {
        return '<a href="/user/' . $this->id . '">@' . $this->name . '</a>';
    }

    public function getProfileCache()
    {
        if ($profile = $this->hasOne(Profile::class)->first()) {
            return $profile;
        }
        //确保profile数据完整
        $profile = new Profile();
        $profile->user_id = $this->id;
        $profile->save();
        return $profile;
    }

    public function ta()
    {
        return $this->isSelf() ? '我' : '他';
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->id;
    }

    public function getExchangeRateCache()
    {
        return Exchange::RATE;
    }

    public function getTotalContributionCache()
    {
        //TODO: sum比取last费时，可以用total替代这个...
        return $this->contributes()->sum('amount');
    }

    public function getIsFollowedCache()
    {
        //FIXME: fixme
        return false;
    }

    public function getIsEditorCache()
    {
        return $this->role_id >= 1;
    }

    public function getIsAdminCache()
    {
        return $this->role_id >= 2;
    }

    public function getAvatarUrlCache()
    {
        if (isset($this->avatar)) {
            if (Str::contains($this->avatar, 'http')) {
                return $this->avatar;
            }
            return \Storage::cloud()->url($this->avatar);
        }
        // 避免前端取不到数据
        return \Storage::cloud()->url(User::AVATAR_DEFAULT);
    }

    public function getTokenCache()
    {
        return $this->api_token;
    }

    public function getBalanceCache()
    {
        $balance = 0;
        $wallet = $this->wallet;
        if (!$wallet) {
            return 0;
        }

        $last = $wallet->transactions()->orderBy('id', 'desc')->first();
        if ($last) {
            $balance = $last->balance;
        }
        return $balance;
    }

    public function getFollowedIdCache()
    {
        if ($user = checkUser()) {
            $follow = Follow::where([
                'user_id' => $user->id,
                'followed_type' => 'users',
                'followed_id' => $this->id,
            ])->select('id')->first();
            if (!is_null($follow)) {
                return $follow->id;
            }
        }
        return null;
    }

    // public function getQqCache()
    // {
    //     return $this->profile->qq;
    // }

    // public function getJsonCache()
    // {
    //     return $this->profile->Json;
    // }

    public function getIntroductionCache()
    {
        if (!$this->profile || empty($this->profile->introduction)) {
            return '这个人很懒，一点介绍都没留下...';
        }
        return $this->profile->introduction;
    }

    //unreads
    public function getUnreadCommentsCache()
    {
        return $this->unreads('comments');
    }

    public function getUnreadLikesCache()
    {
        return $this->unreads('likes');
    }

    public function getUnreadChatCache()
    {
        return $this->unreads('chat');
    }
    public function getUnreadFollowsCache()
    {
        return $this->unreads('follows');
    }

    public function getUnreadRequestsCache()
    {
        return $this->unreads('requests');
    }

    public function getUnreadTipsCache()
    {
        return $this->unreads('tips');
    }

    public function getUnreadOthersCache()
    {
        return $this->unreads('others');
    }

    public function getCountCategoriesCache()
    {
        return $this->categories()->count() ?? 0;
    }

    public function getCountProductionCache()
    {
        return $this->articles()->count();
    }

    public function getCountFollowersCache()
    {
        return $this->count_follows;
    }

    public function getCountFollowingsCache()
    {
        return $this->followingUsers()->count();
    }

    public function getCountDraftsCache()
    {
        return $this->drafts()->count();
    }

    public function getRewardCache()
    {
        return $this->getBalanceCache();
        //临时过渡使用
        //        $gold = $this->gold;
        //        if($gold<600){
        //            return 0;
        //        }
        //        return intval($gold/600);
    }

    //TODO: 这些可以后面淘汰，前端直接访问 user->profile->atts 即可
    public function getCountArticlesCache()
    {
        return $this->allArticles()->count();
        // return $this->profile->count_articles;
    }

    public function getCountLikesCache()
    {
        return $this->profile->count_likes;
    }
    public function getCountFollowsCache()
    {
        return $this->profile->count_follows;
    }

    public function getCountCollectionsCache()
    {
        return $this->profile->count_collections;
    }

    public function getCountFavoritesCache()
    {
        return $this->profile->count_favorites;
    }

    public function getGenderCache()
    {
        return $this->profile->gender;
    }

    public function getGenderMsgCache()
    {
        switch ($this->profile->gender) {
            case User::MALE_GENDER:
                return '男';
                break;
            case User::FEMALE_GENDER:
                return '女';
                break;
            default:
                return "女";
        }
    }

    public static function getGenderNumber($gender)
    {
        switch ($gender) {
            case '男':
                return self::MALE_GENDER;
                break;
            case '女':
                return self::FEMALE_GENDER;
                break;
            default:
                return self::FEMALE_GENDER;
        }
    }

    public function getAgeCache()
    {
        $birthday = Carbon::parse($this->birthday);
        return $birthday->diffInYears(now(), false);
    }

    public function getBirthdayMsgCache()
    {
        return date('Y-m-d', strtotime($this->birthday));
    }

    public function getBirthdayCache()
    {
        return $this->profile->birthday;
    }

    public function getContributeCache()
    {
        return $this->profile->count_contributes;
    }

    public function getTodayContributeCache()
    {
        $amount = Contribute::where('user_id', $this->id)->where('amount', '>', 0)->whereDate('created_at', today())->sum('amount');
        if ($amount <= 0) {
            return 0;
        }
        return $amount;
    }

    //默认都自动绑定懂得赚
    public function getIsBindDongdezhuanCache()
    {
        return true;
    }

    public function getDongdezhuanUserCache()
    {
        // return $this->getDDZUser();
        return $this;
    }

    public function getForceAlertCache()
    {
        return $this->isWithdrawBefore();
    }

    public function getTitlePhoneCache()
    {
        if ($this->phone !== null) {
            return substr_replace($this->phone, '****', 3, 4);
        }
        return null;
    }

    //根据用户类型（老用户，新用户，刷子），返回提现需要的贡献值
    function getUserWithdrawDate()
    {
        if (empty($this->wallet) || $this->wallet->total_withdraw_amount < 2) {
            //新用户
            return Contribute::WITHDRAW_DATE;
        } else if ($this->wallet->total_withdraw_amount < 5) {
            //老用户
            return Contribute::WITHDRAW_DATE * 2;
        } else {
            //刷子
            return Contribute::WITHDRAW_DATE * 3;
        }
    }
}
