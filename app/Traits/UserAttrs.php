<?php

namespace App\Traits;

use App\Follow;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait UserAttrs
{
    public function getIsFollowedAttribute()
    {
        //TODO: fixme
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

    public function getQqAttribute()
    {
        return $this->profile->qq;
    }

    public function getJsonAttribute()
    {
        return $this->profile->Json;
    }

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

    public function getAgeAttribute()
    {
        $birthday = Carbon::parse($this->birthday);
        return $birthday->diffInYears(now(), false);
    }

    public function getBirthdayMsgAttribute()
    {
        return date('Y-m-d', strtotime($this->birthday));
    }
}
