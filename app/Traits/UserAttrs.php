<?php

namespace App\Traits;

use App\BlackList;
use App\Exchange;
use App\Follow;
use App\Profile;
use App\User;
use App\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UserAttrs
{
    public function getGoldAttribute()
    {
        return $this->goldWallet->goldBalance;
    }

    //rmb钱包，默认钱包
    public function getWalletAttribute()
    {
        if ($wallet = $this->wallets()->whereType(0)->first()) {
            return $wallet;
        }

        return Wallet::rmbWalletOf($this);
    }

    public function getIsExchangeTodayAttribute()
    {
        return $this->exchanges()->whereDate('created_at', \Carbon\Carbon::today())->count() > 0;
    }

    //兼容前端:下一版去掉
    public function getIsWalletAttribute()
    {
        $wallet = $this->wallets()->whereType(0)->first();
        if ($wallet->pay_account) {
            return $wallet;
        } else {
            return null;
        }
    }

    //金币钱包
    public function getGoldWalletAttribute()
    {
        if ($wallet = $this->wallets()->whereType(1)->first()) {
            return $wallet;
        }
        return Wallet::goldWalletOf($this);
    }

    //TODO 临时过渡
    public function getCashAttribute()
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

        $black    = BlackList::where('user_id', $this->id);
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

    public function getProfileAttribute()
    {
        if ($profile = $this->hasOne(Profile::class)->first()) {
            return $profile;
        }
        //确保profile数据完整
        $profile          = new Profile();
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

    public function getExchangeRateAttribute()
    {
        return Exchange::RATE;
    }

    public function getTotalContributionAttribute()
    {
        return $this->contributes()->sum('amount');
    }

    public function getIsFollowedAttribute()
    {
        //FIXME: fixme
        return false;
    }

    public function getIsEditorAttribute()
    {
        return $this->role_id >= 1;
    }

    public function getIsAdminAttribute()
    {
        return $this->role_id >= 2;
    }

    public function getAvatarUrlAttribute()
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

    public function getTokenAttribute()
    {
        return $this->api_token;
    }

    public function getBalanceAttribute()
    {
        $balance = 0;
        $wallet  = $this->wallet;
        if (!$wallet) {
            return 0;
        }

        $last = $wallet->transactions()->orderBy('id', 'desc')->first();
        if ($last) {
            $balance = $last->balance;
        }
        return $balance;
    }

    public function getFollowedIdAttribute()
    {
        if ($user = checkUser()) {
            $follow = Follow::where([
                'user_id'       => $user->id,
                'followed_type' => 'users',
                'followed_id'   => $this->id,
            ])->select('id')->first();
            if (!is_null($follow)) {
                return $follow->id;
            }
        }
        return null;
    }

    // public function getQqAttribute()
    // {
    //     return $this->profile->qq;
    // }

    // public function getJsonAttribute()
    // {
    //     return $this->profile->Json;
    // }

    public function getIntroductionAttribute()
    {
        if (!$this->profile || empty($this->profile->introduction)) {
            return '这个人很懒，一点介绍都没留下...';
        }
        return $this->profile->introduction;
    }

    //unreads
    public function getUnreadCommentsAttribute()
    {
        return $this->unreads('comments');
    }

    public function getUnreadLikesAttribute()
    {
        return $this->unreads('likes');
    }

    public function getUnreadChatAttribute()
    {
        return $this->unreads('chat');
    }
    public function getUnreadFollowsAttribute()
    {
        return $this->unreads('follows');
    }

    public function getUnreadRequestsAttribute()
    {
        return $this->unreads('requests');
    }

    public function getUnreadTipsAttribute()
    {
        return $this->unreads('tips');
    }

    public function getUnreadOthersAttribute()
    {
        return $this->unreads('others');
    }

    public function getCountCategoriesAttribute()
    {
        return $this->categories()->count() ?? 0;
    }

    public function getCountProductionAttribute()
    {
        return $this->articles()->count();
    }

    public function getCountFollowersAttribute()
    {
        return $this->count_follows;
    }

    public function getCountFollowingsAttribute()
    {
        return $this->followingUsers()->count();
    }

    public function getCountDraftsAttribute()
    {
        return $this->drafts()->count();
    }

    public function getRewardAttribute()
    {
        return $this->getBalanceAttribute();
        //临时过渡使用
        //        $gold = $this->gold;
        //        if($gold<600){
        //            return 0;
        //        }
        //        return intval($gold/600);
    }

    //TODO: 这些可以后面淘汰，前端直接访问 user->profile->atts 即可
    public function getCountArticlesAttribute()
    {
        return $this->profile->count_articles;
    }

    public function getCountLikesAttribute()
    {
        return $this->profile->count_likes;
    }
    public function getCountFollowsAttribute()
    {
        return $this->profile->count_follows;
    }

    public function getCountCollectionsAttribute()
    {
        return $this->profile->count_collections;
    }

    public function getCountFavoritesAttribute()
    {
        return $this->profile->count_favorites;
    }

    public function getGenderAttribute()
    {
        return $this->profile->gender;
    }

    public function getGenderMsgAttribute()
    {
        switch ($this->profile->gender) {
            case self::MALE_GENDER:
                return '男';
                break;
            case self::FEMALE_GENDER:
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

    public function getAgeAttribute()
    {
        $birthday = Carbon::parse($this->birthday);
        return $birthday->diffInYears(now(), false);
    }

    public function getBirthdayMsgAttribute()
    {
        return date('Y-m-d', strtotime($this->birthday));
    }

    public function getBirthdayAttribute()
    {
        return $this->profile->birthday;
    }
}
