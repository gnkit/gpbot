<?php

namespace App\Actions\Telegraph;

use DefStudio\Telegraph\Models\TelegraphChat;

final class GetChatByChatIdAction
{
    public static function execute($chatId): TelegraphChat
    {
        return TelegraphChat::where('chat_id', '=', $chatId)->first();
    }
}
