<?php

namespace App\Actions\Account;

use App\Models\Account;

final class GetAccountByProductIdAction
{
    public static function execute($accountId)
    {
        return Account::with('product')->where('id', '=', $accountId)->first();
    }
}
