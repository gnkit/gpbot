<?php

namespace App\Actions\Account;

use App\Models\Account;

final class GetAccountByChatIdAction
{
    public static function execute($chatId): ?Account
    {
        return Account::where('chat_id', '=', $chatId)->first();
    }
}
