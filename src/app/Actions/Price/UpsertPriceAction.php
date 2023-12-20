<?php

namespace App\Actions\Price;

use App\DataTransferObjects\PriceData;
use App\Models\Price;

final class UpsertPriceAction
{
    public static function execute(PriceData $data): Price
    {
        return Price::updateOrCreate(
            [
                'id' => $data->id,
            ],
            [
                'product_id' => $data->product_id,
                'value' => $data->value,
            ],
        );
    }
}
