<?php

namespace App\Actions\Product;

use App\Models\Product;

final class GetProductByAccountIdAction
{
    public static function execute($account_id): ?Product
    {
        return Product::where('account_id', '=', $account_id)->first();
    }
}
