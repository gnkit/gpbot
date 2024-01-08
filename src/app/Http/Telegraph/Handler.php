<?php

namespace App\Http\Telegraph;

use App\Actions\Account\GetAccountByChatIdAction;
use App\Actions\Account\UpsertAccountAction;
use App\Actions\Price\GetPriceByProductIdAction;
use App\Actions\Product\DestroyProductAction;
use App\Actions\Product\GetProductByAccountIdAction;
use App\Actions\Product\UpsertProductAction;
use App\DataTransferObjects\AccountData;
use App\DataTransferObjects\ProductData;
use App\Models\Account;
use App\Models\Product;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Stringable;

final class Handler extends WebhookHandler
{
    public function start(): void
    {
        if (null !== $this->getAccount($this->message->from()->id())) {

            $this->chat->message("Сәлем, " . $this->message->from()->username() . "! \nМеню арқылы қажетті нұсқауды таңдаңыз.")->send();
        } else {
            $data = AccountData::from([
                'chat_id' => $this->message->from()->id(),
                'username' => $this->message->from()->username() ?? '',
                'firstname' => $this->message->from()->firstName() ?? '',
                'lastname' => $this->message->from()->lastName() ?? '',
                'type' => $this->message->chat()->type() ?? '',
            ]);

            UpsertAccountAction::execute($data);

            $this->chat->message("Сәлем, бағаны бақылау қызметіне қош келдіңіз! \n\nМеню арқылы қажетті нұсқауды таңдаңыз.")->send();
        }
    }

    public function add(): void
    {
        if (null !== $this->accountHasProduct($this->message->from()->id())) {

            $this->reply("Қазіргі уақытта сіздің сілтемеңіз белсенді. \nСілтемеңізді тек өшіру арқылы жаңарта аласыз.");
        } else {

            $this->chat->message('Сілтемені енгізіңіз.')->send();
        }

    }

    public function product(): void
    {
        if (null !== $this->accountHasProduct($this->message->from()->id())) {

            $product = GetProductByAccountIdAction::execute((GetAccountByChatIdAction::execute($this->message->from()->id()))->id);
            $this->chat->message($product->link)->send();
        } else {

            $this->chat->message('Сізге сілтеме енгізу керек.')->send();
        }

    }

    public function price(): void
    {
        if (null !== $this->accountHasProduct($this->message->from()->id())) {
            $product = $this->accountHasProduct($this->message->from()->id());
            $prices = [];
            foreach ($product->prices as $key => $price) {
                $prices[] = '📆 ' . Carbon::parse($price->created_at)->isoFormat('D MMMM, YYYY');
                $prices[] = '💵 <b>' . round($price->value) . '</b> &#8376;' . PHP_EOL;
            }
            $priceHtml = implode("\n", $prices);

            $this->chat->message($priceHtml)->send();
        } else {

            $this->chat->message('Сізде көрсететін баға жоқ.')->send();
        }
    }

    public function delete(): void
    {
        $product = $this->accountHasProduct($this->message->from()->id());
        if (null !== $product) {
            DestroyProductAction::execute($product);

            $this->chat->message('Сіздің сілтемеңіз сәтті жойылды.')->send();
        } else {

            $this->chat->message('Сізде жойылатын сілтеме жоқ.')->send();
        }

    }

    public function help(): void
    {
        $this->chat->message("help")->send();
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->reply('Белгісіз нұсқау.');
    }

    public function handleChatMessage(Stringable $text): void
    {
        if (filter_var($text->value(), FILTER_VALIDATE_URL)) {
            $this->reply('Сіздің сілтемеңіз өңделуде...');

            sleep(1);

            if (null !== $this->accountHasProduct($this->message->from()->id())) {

                $this->reply("Қазіргі уақытта сіздің сілтемеңіз белсенді. \nСілтемеңізді тек өшіру арқылы жаңарта аласыз.");
            } else {

                $data = ProductData::from([
                    'account_id' => (GetAccountByChatIdAction::execute($this->message->from()->id()))->id,
                    'link' => $this->message->text(),
                ]);

                $product = UpsertProductAction::execute($data);

                sleep(1);

                $this->reply('Сіздің сілтемеңіз қабылданды. Жауапты күтіңіз...');

                sleep(5);

                $price = GetPriceByProductIdAction::execute($product->id);

                sleep(2);

                $html = '📆 ' . Carbon::parse($price->created_at)->isoFormat('D MMMM, YYYY') . PHP_EOL
                    . '💵 <b>' . round($price->value) . '</b> &#8376;' . PHP_EOL;

                $this->reply('Сіздің тауарыңыз сәтті тіркелді. Бастапқы бағасы төмендегідей:' . PHP_EOL . $html);

            }
        } else {

            $this->reply('Тек белгілі нұсқауларды беріңіз.');
        }
    }

    public function sendPrice($newPrice)
    {
        $this->chat->message($newPrice)->send();
    }

    private function accountHasProduct($accountId): ?Product
    {
        return GetProductByAccountIdAction::execute(($this->getAccount($accountId))?->id);
    }

    private function getAccount($accountId): ?Account
    {
        return GetAccountByChatIdAction::execute($accountId);
    }

}
