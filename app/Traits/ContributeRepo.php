<?php

    namespace App\Traits;

    use App\Contribute;
    use App\User;

    trait ContributeRepo
    {
        public static function rewardUserContribute($user_id, $id, $amount, $type)
        {
            $contribute = Contribute::create(
                [
                    'user_id' => $user_id,
                    'contributed_id' => $id,
                    'contributed_type' => $type,
                    'amount' => $amount
                ]
            );
            $contribute->recountUserContribute();
            return $contribute;
        }

        public static function getCountByType(string $type, User $user)
        {
            return Contribute::where([
                'contributed_type' => $type,
                'user_id' => $user->id,
            ])->count();
        }
    }
