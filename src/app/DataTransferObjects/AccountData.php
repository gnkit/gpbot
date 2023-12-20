<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

final class AccountData extends Data
{
    public function __construct(
        public readonly ?int   $id,
        public readonly int    $chat_id,
        public readonly string $username,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $type,
    )
    {
    }
}
