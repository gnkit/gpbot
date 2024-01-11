<?php

namespace App\Actions\Scraper;

use App\Actions\Account\GetAccountByProductIdAction;
use App\Actions\Price\GetDifferencePercentOfPriceAction;
use App\Actions\Price\GetLastPriceByProductIdAction;
use App\Actions\Price\UpsertPriceAction;
use App\Actions\Product\GetAllProductAction;
use App\Actions\Telegraph\GetChatByChatIdAction;
use App\DataTransferObjects\PriceData;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;

final class UpsertPriceAllProductScraperAction
{
    /**
     * @return void
     * @throws GuzzleException
     */
    public static function execute(): void
    {
        $products = GetAllProductAction::execute();

        foreach ($products->chunk(50) as $chunk) {
            foreach ($chunk as $product) {
                $newPriceValue = GetPriceScraperAction::execute($product->link);
                $currentPrice = GetLastPriceByProductIdAction::execute($product->id);

                if ($newPriceValue != $currentPrice?->value) {
                    UpsertPriceAction::execute(PriceData::from([
                        'product_id' => $product->id,
                        'value' => $newPriceValue,
                    ]));

                    $account = GetAccountByProductIdAction::execute($product->account_id);
                    $chat = GetChatByChatIdAction::execute($account->chat_id);
                    $difference = GetDifferencePercentOfPriceAction::execute($currentPrice?->value, $newPriceValue);
                    $differenceHtml = ($currentPrice->value > $newPriceValue)
                        ? '📉 ' . $difference . '% төмендеді.'
                        : '📈 ' . $difference . '% көтерілді.';

                    $html = '📆 ' . Carbon::parse('now')->isoFormat('D MMMM, YYYY') . PHP_EOL
                        . '💵 <b>' . round($newPriceValue) . '</b> &#8376;' . PHP_EOL
                        . $differenceHtml . PHP_EOL;

                    $chat->message($html)->send();
                }
            }
        }
    }
}
