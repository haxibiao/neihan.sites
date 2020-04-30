<?php

namespace App\Observers;

use App\PlatformAccount;
use App\Product;

class PlatformAccountObServer
{
    //
    public function created(PlatformAccount $platformAccount)
    {
        //每创建一个账号，商铺游戏账号库存数量,总数量+1
        $product = Product::find($platformAccount->product_id);
        if (!empty($product)) {
            if ($platformAccount->order_status == PlatformAccount::UNUSE) {
                $product->available_amount = $product->available_amount + 1;
            }
            $product->amount = $product->amount + 1;
            $product->save();
        }
    }

    /**
     * Handle the like "updated" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function updating(PlatformAccount $platformAccount)
    {
        $product = Product::find($platformAccount->product_id);
        //未变更数量前的商品数据
        $p = PlatformAccount::find($platformAccount->id);
        if (!empty($product) && !empty($p)) {
            if ($platformAccount->order_status == PlatformAccount::UNUSE) {
                $product->available_amount = $product->available_amount + 1;
            } else if ($platformAccount->order_status == PlatformAccount::INUSE
                && $p->order_status == PlatformAccount::UNUSE) {
                //状态改成使用中，改动之前状态必须是待使用
                $product->available_amount = $product->available_amount - 1;
            } else if ($platformAccount->order_status == PlatformAccount::EXPIRE
                && $p->order_status == PlatformAccount::UNUSE) {
                //状态改成已过期，并且状态改动之前是待使用
                $product->available_amount = $product->available_amount - 1;
            }
            $product->save();
        }

    }

    /**
     * Handle the like "deleted" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function deleted(PlatformAccount $platformAccount)
    {
        $product = Product::find($platformAccount->product_id);
        if (!empty($product)) {
            if ($platformAccount->order_status == PlatformAccount::UNUSE) {
                $product->available_amount = $product->available_amount - 1;
            }
            //兼容nova脏数据，删除时‘上架数’变负数
            $product->amount = $product->amount - 1;
            if ($product->amount < 0) {
                $product->amount = 0;
            }
            if ($product->available_amount < 0) {
                $product->available_amount = 0;
            }

            $product->save();
        }
    }
}
