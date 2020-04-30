<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\PlatformAccount;

trait PlatformAccountRepo
{
    public function getDimensionByType($type, $product_id)
    {
        return PlatformAccount::where("product_id", $product_id)
            ->whereNotNull($type)
            ->where($type, "!=", "")
            ->groupBy($type)
            ->pluck($type);
    }

    public function getPriceByDimension($product_id, $dimension, $dimension2)
    {
        $qb = PlatformAccount::where("product_id", $product_id)
            ->where("dimension", $dimension)
            ->where("dimension2", $dimension2)->first();
        $count = PlatformAccount::where("product_id", $product_id)
            ->where("dimension", $dimension)
            ->where("dimension2", $dimension2)->count();

        if (empty($qb) || $count <= 0) {
            throw new GQLException("该规格的账号库存不够啦！");
        }
        $qb->count = $count;
        return $qb;

    }

}
