<?php

namespace App\Actions\Scraper;

use App\Actions\Price\GetLastPriceByProductIdAction;
use App\Actions\Price\UpsertPriceAction;
use App\Actions\Product\GetAllProductAction;
use App\DataTransferObjects\PriceData;
use GuzzleHttp\Exception\GuzzleException;

final class UpsertPriceAllProductScraperAction
{
    /**
     * @return void
     * @throws GuzzleException
     */
    public static function execute(): void
    {
        $products = GetAllProductAction::execute();

        foreach ($products->chunk(50) as $chunk) {
            foreach ($chunk as $product) {
                $newPriceValue = GetPriceScraperAction::execute($product->link);
                $currentPrice = GetLastPriceByProductIdAction::execute($product->id);

                if ($newPriceValue != $currentPrice->value) {
                    UpsertPriceAction::execute(PriceData::from([
                        'product_id' => $product->id,
                        'value' => $newPriceValue,
                    ]));
                }
            }
        }
    }
}
