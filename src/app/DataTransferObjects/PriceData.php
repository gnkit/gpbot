<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

final class PriceData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly int  $product_id,
        public readonly int  $value,
    )
    {
    }
}
