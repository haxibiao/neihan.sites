<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Gold;
use App\Jobs\OrderAutoExpire;
use App\Notifications\PlatformAccountExpire;
use App\Order;
use App\PlatformAccount;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait OrderRepo
{
    public function createOrder($product_id, $dimension, $dimension2 = 1)
    {
        //规格1:皮肤名   dimension
        //规格2:租借时间   dimension2
        $user = getUser();

        //是否下架
        $product = Product::where("id", $product_id)
            ->where("status", 1)
            ->first();

        if (empty($product)) {
            throw new GQLException("该商品已下架！");
        }

        //是否还有库存
        $platform_account = PlatformAccount::where("product_id", $product_id)
            ->where("order_status", PlatformAccount::UNUSE)
            ->where("dimension", $dimension)
            ->where("dimension2", $dimension2)
            ->first();

        if (empty($platform_account)) {
            throw new GQLException("该规格的账号没有啦！请选择其他规格吧！");
        }

        if ($user->gold < $platform_account->price) {
            throw new GQLException('您的金币不足!');
        }

        if (empty($user) || is_null($user->phone)) {
            throw new GQLException("请先去绑定手机号再来租号吧！");
        }

        //每买一次数量-1
        $product->update(["available_amount", $product->available_amount - 1]);

        // 租号
        return $this->freeBorrow($product, $platform_account, $dimension2);
    }

    //免费租号
    public function freeBorrow($product, $platform_account, $dimension2)
    {
        $user = getUser();
        //新人只能参与免费租号一次
        $order = Order::where("user_id", $user->id)->first();
        if (!empty($order) && $platform_account->price == 0) {
            throw new GQLException("新人只能参与一次免费租号活动");
        }
        DB::beginTransaction();
        try {
            //1.取出'未使用'的账号返回给用户
            //上面已经拿到了platform_account

            //2.创建订单
            $order = Order::create([
                "user_id" => $user->id,
                "number"  => time(),
                "status"  => Order::PAID,
            ]);

            //3.更新order和product的关联
            $order->products()->syncWithoutDetaching([
                $product->id => [
                    'amount' => 1,
                    'price'  => $platform_account->price,
                ],
            ]);

            //4.更新账号为使用中
            $platform_account->update([
                "order_status" => PlatformAccount::INUSE,
                "order_id"     => $order->id,
                "user_id"      => $user->id,
            ]);

            //5.扣除智慧点
            Gold::makeOutcome($user, $platform_account->price, '租借账号扣除');

            //6.通知用户(订单剩余十分钟)
            $user->notify((new PlatformAccountExpire($platform_account))->delay(now()
                    ->addHour($dimension2)->subMinutes(10)));
            //更新订单和账号状态为已过期
            \dispatch(new OrderAutoExpire($platform_account, $order))
                ->delay(now()->addHour($dimension2));

            DB::commit();
            return $order;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            throw new GQLException("未知异常，订单取消");
        }

    }
}
