<?php

namespace App\DDZ;

use Psy\Util\Str;

trait UserAttrs
{
    //rmb钱包，默认钱包
    public function getWalletAttribute()
    {
        if ($wallet = $this->wallets()->whereType(0)->first()) {
            return $wallet;
        }

        return Wallet::rmbWalletOf($this);
    }

    public function getAvatarUrlAttribute()
    {
        if (\Illuminate\Support\Str::contains($this->avatar, 'http')) {
            return $this->avatar;
        }
        // 懂得赚 filesystem cdn url
        return 'http://cos-dongdezhuan.dianmoge.com/' . $this->avatar;
    }

    public function getMyInviterAttribute()
    {
        $invitation = Invitation::where('be_inviter_id', $this->id)
            ->whereNotNull('invited_in')
            ->first();

        return data_get($invitation, 'user');
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

    // 根据提现阶段获取当前广告的间隔时间 毫秒
    public function getAdDurationAttribute()
    {
        $withdrawPhase = $this->wallet->withdraw_phase;
        $seconds = 60;
        switch ($withdrawPhase){
            case 5:
                $seconds = 90;
                break;
            case 20:
                $seconds = 120;
                break;
            case 50:
                $seconds = 150;
                break;
            case 100:
                $seconds = 180;
                break;
            case 200:
                $seconds = 210;
                break;
            case 500:
                $seconds = 240;
                break;
        }

        return $seconds * 1000;
    }
}
