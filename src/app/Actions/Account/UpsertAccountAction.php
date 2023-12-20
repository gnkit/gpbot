<?php

namespace App\Actions\Account;

use App\DataTransferObjects\AccountData;
use App\Models\Account;

final class UpsertAccountAction
{
    public static function execute(AccountData $data): Account
    {
        return Account::updateOrCreate(
            [
                'id' => $data->id,
            ],
            [
                'chat_id' => $data->chat_id,
                'username' => $data->username,
                'firstname' => $data->firstname,
                'lastname' => $data->lastname,
                'type' => $data->type,
            ],
        );
    }
}
