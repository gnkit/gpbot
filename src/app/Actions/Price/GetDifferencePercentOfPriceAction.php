<?php

namespace App\Actions\Price;

final class GetDifferencePercentOfPriceAction
{
    public static function execute($currentPrice, $newPrice): float
    {
        return round(((abs($currentPrice - $newPrice)) / $currentPrice) * 100, 2);
    }
}
