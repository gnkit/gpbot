<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

final class ProductData extends Data
{
    public function __construct(
        public readonly ?int   $id,
        public readonly int    $account_id,
        public readonly string $link,
    )
    {
    }
}
