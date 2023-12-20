<?php

namespace App\Http\Telegraph;

use App\Actions\Account\GetAccountChatIdAction;
use App\Actions\Account\UpsertAccountAction;
use App\Actions\Product\GetProductByAccountIdAction;
use App\Actions\Product\UpsertProductAction;
use App\DataTransferObjects\AccountData;
use App\DataTransferObjects\ProductData;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

final class Handler extends WebhookHandler
{
    public function start(): void
    {
        if (null !== GetAccountChatIdAction::execute($this->message->from()->id())) {

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
        if (null !== GetProductByAccountIdAction::execute((GetAccountChatIdAction::execute($this->message->from()->id()))->id)) {

            $this->reply("Қазіргі уақытта сіздің сілтемеңіз белсенді. \nСілтемеңізді тек өшіру арқылы жаңарта аласыз.");
        } else {

            $this->chat->message('Сілтемені енгізіңіз.')->send();
        }

    }

    public function product(): void
    {
        $this->chat->message("product")->send();
    }

    public function price(): void
    {
        $this->chat->message("price")->send();
    }

    public function delete(): void
    {
        $this->chat->message("delete")->send();
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

            if (null !== GetProductByAccountIdAction::execute((GetAccountChatIdAction::execute($this->message->from()->id()))->id)) {

                $this->reply("Қазіргі уақытта сіздің сілтемеңіз белсенді. \nСілтемеңізді тек өшіру арқылы жаңарта аласыз.");
            } else {

                $data = ProductData::from([
                    'account_id' => (GetAccountChatIdAction::execute($this->message->from()->id()))->id,
                    'link' => $this->message->text(),
                ]);

                UpsertProductAction::execute($data);

                sleep(1);

                $this->reply('Сіздің сілтемеңіз қабылданды. Жауапты күтіңіз...');
            }
        } else {

            $this->reply('Тек белгілі нұсқауларды беріңіз.');
        }
    }

}
