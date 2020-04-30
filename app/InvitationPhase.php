<?php

namespace App;

use App\Exceptions\GQLException;
use App\UserInvitation;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvitationPhase extends Model
{
    protected $fillable = [
        'amount',
        'rate',
    ];

    const DEFAULT_PHASE_ID = 1;

    public static function reward(UserInvitation $userInvitation)
    {
        return null;
//        $unionWallet = $userInvitation->getUnionWallet();
//        $phase       = $userInvitation->phase;
//        //联盟钱包
//        if ($unionWallet->isUnionWallet() && !is_null($phase)) {
//            $balance   = $unionWallet->balance;
//            $rmbWallet = $userInvitation->rmbWallet;
//            $isSuccess = false;
//            //超期>=目标 自动转入钱包
//            if ($balance >= $phase->amount && !is_null($rmbWallet)) {
//                DB::beginTransaction();
//                try {
//                    Transaction::makeIncome($rmbWallet, $balance, '联盟钱包奖金兑换');
//                    Transaction::makeOutcome($unionWallet, $balance, '联盟钱包奖金兑换扣款');
//                    DB::commit();
//                    $isSuccess = true;
//                } catch (Exception $ex) {
//                    DB::rollback();
//                    Log::error($ex->getMessage());
//                }
//
//                if ($isSuccess) {
//                    $incrementRate = $userInvitation->rate - $phase->rate;
//                    $nextPhase     = InvitationPhase::nextPhase($phase->id);
//                    if (!is_null($nextPhase)) {
//                        $userInvitation->rate     = $incrementRate + $nextPhase->rate;
//                        $userInvitation->phase_id = $nextPhase->id;
//                        $userInvitation->save();
//
//                        return $userInvitation;
//                    }
//                }
//
//            }
//        }
    }

    public static function nextPhase($phaseId)
    {
        return InvitationPhase::where('id', '>', $phaseId)->first();
    }
}
