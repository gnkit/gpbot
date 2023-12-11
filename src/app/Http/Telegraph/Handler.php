<?php

namespace App\Http\Telegraph;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Stringable;

final class Handler extends WebhookHandler
{
    public function start(): void
    {
        $this->chat->html('Сәлем, бағаны бақылау қызметіне қош келдіңіз!')->send();

        sleep(1);

        $this->chat->message('Мәзір арқылы қажетті нұсқауды таңдаңыз.')
            ->replyKeyboard(ReplyKeyboard::make()
                ->row([
                    ReplyButton::make('Сілтеме'),
                    ReplyButton::make('Баға'),
                    ReplyButton::make('Көмек'),
                    ReplyButton::make('Жою'),
                ])
                ->resize()
                ->oneTime()
            )->send();
    }

    protected function handleUnknownCommand(Stringable $text): void
    {
        if ($text->value() === '/start') {
            $this->reply('Саламатсызба');
        } else {
            $this->reply('Белгісіз нұсқау');
        }
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->reply('Тек белгілі нұсқауларды беріңіз.');
    }
}
