<?php

namespace App\Actions\Price;

use App\Models\Price;

final class GetLastPriceByProductIdAction
{
    public static function execute($productId): ?Price
    {
        return Price::where('product_id', '=', $productId)->latest('id')->first();
    }
}
