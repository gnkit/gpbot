<?php

namespace App\Actions\Product;

use App\Models\Product;

final class DestroyProductAction
{
    public static function execute(Product $product): void
    {
        $product = Product::findOrFail($product->id);
        $product->delete();
    }
}
