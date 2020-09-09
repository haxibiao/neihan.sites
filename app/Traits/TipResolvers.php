<?php


namespace App\Traits;


use App\Exceptions\GQLException;
use App\Exceptions\UserException;
use App\Gold;
use App\Notifications\ArticleTiped;
use App\Tip;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TipResolvers
{
    public function create($root, array $args, $context)
    {
        $user = getUser();
        DB::beginTransaction();
        try {
            $gold = Arr::get($args, 'gold', 0);
            $tip  = new Tip();
            //打赏(金币)
            if ($gold > 0) {
                $tip->user_id      = $user->id;
                $tip->gold         = $gold;
                $tip->message      = Arr::get($args, 'message');
                $tip->tipable_id   = Arr::get($args, 'tipable_id');
                $tip->tipable_type = Arr::get($args, 'tipable_type');
                $tip->save();

                $toUser = ($tip->tipable)->user;

                if ($user->gold < $args['gold']) {
                    throw new UserException('您的金币不足!');
                }
                //扣除金币
                Gold::makeOutcome($user, $args['gold'], '赞赏支付');
                //入账金币
                Gold::makeIncome($toUser, $args['gold'], '收到赞赏奖励');

            }
            DB::commit();

            //消息通知
            $toUser->notify(new ArticleTiped($tip->tipable, $user, $tip));

            return $tip;
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($ex->getCode() == 0) {
                Log::error($ex->getMessage());
                throw new GQLException('程序小哥正在加紧修复中!');
            }
            throw new GQLException($ex->getMessage());
        }
    }
}