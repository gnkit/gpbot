<?php

namespace App\Actions\Price;

final class GetDifferencePercentOfPriceAction
{
    public static function execute($currentPrice, $newPrice): float
    {
        if ($currentPrice > $newPrice) {

            return round(($currentPrice / ($currentPrice - $newPrice)) / 100, 2);
        } else {

            return round(($newPrice / ($newPrice - $currentPrice)) / 100, 2);
        }
    }
}
