<?php

namespace App\Traits;

use App\CheckIn;
use App\Exceptions\UserException;
use Illuminate\Support\Arr;

trait CheckInResolvers
{

    public function resolveStore($root, array $args, $context, $info): CheckIn
    {
        $user   = getUser();
        $signIn = $user->checkIns()->where('created_at', '>=', today())->first();
        if (!is_null($signIn)) {
            throw new UserException('已经签到过了,请勿重复签到哦!');
        }
        return CheckIn::checkIn($user);
    }

    public function resolveSignIns($root, array $args, $context, $info)
    {
        $user = getUser();
        $days = Arr::get($args, 'days', 7);
        return CheckIn::getSignIns($user, $days);
    }
}
