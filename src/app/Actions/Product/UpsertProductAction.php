<?php

namespace App\Actions\Product;

use App\Actions\Price\UpsertPriceAction;
use App\DataTransferObjects\PriceData;
use App\DataTransferObjects\ProductData;
use App\Models\Product;

final class UpsertProductAction
{
    public static function execute(ProductData $data): Product
    {
        $product = Product::updateOrCreate(
            [
                'id' => $data->id,
            ],
            [
                'account_id' => $data->account_id,
                'link' => $data->link,
            ],
        );

        $price = 100;

        $priceData = PriceData::from([
            'product_id' => $product->id,
            'value' => $price,
        ]);

        UpsertPriceAction::execute($priceData);

        return $product;
    }
}
