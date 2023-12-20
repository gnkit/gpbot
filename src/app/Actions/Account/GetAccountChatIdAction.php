<?php

namespace App\Actions\Account;

use App\Models\Account;

final class GetAccountChatIdAction
{
    public static function execute($chat_id): ?Account
    {
        return Account::where('chat_id', '=', $chat_id)->first();
    }
}
