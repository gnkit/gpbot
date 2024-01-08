<?php

namespace App\Actions\Product;

use App\Models\Product;

final class GetProductByAccountIdAction
{
    public static function execute($accountId): ?Product
    {
        return Product::where('account_id', '=', $accountId)->first();
    }
}
