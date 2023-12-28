<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final class GetAllProductAction
{
    public static function execute(): Collection
    {
        return Product::with('prices')->select('account_id', 'link', 'id')->get();
    }
}
