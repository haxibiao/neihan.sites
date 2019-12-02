<?php
namespace App\Traits;

use App\Contribute;

trait ContributeResolvers
{
    public function clickAD($rootValue, array $args, $context, $resolveInfo)
    {
        $user       = checkUser();
        $contribute = Contribute::create([
            'user_id'          => $user->id,
            'amount'           => self::AD_AMOUNT,
            'contributed_id'   => self::AD_CONTRIBUTED_ID,
            'contributed_type' => self::AD_CONTRIBUTED_TYPE,
        ]);
        $contribute->recountUserContribute();

        return $contribute;
    }
}
