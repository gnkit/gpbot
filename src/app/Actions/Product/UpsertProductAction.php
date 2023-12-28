<?php

namespace App\Actions\Product;

use App\Actions\Price\UpsertPriceAction;
use App\Actions\Scraper\GetPriceScraperAction;
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

        $price = GetPriceScraperAction::execute($data->link);

        $priceData = PriceData::from([
            'product_id' => $product->id,
            'value' => $price ?? null,
        ]);

        UpsertPriceAction::execute($priceData);

        return $product;
    }
}
