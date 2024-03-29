<?php

use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('commands', function () {

    Telegraph::registerBotCommands([
        'start' => 'Бот жұмысын бастау',
        'add' => 'Бақыланатын тауар сілтемесін енгізу',
        'product' => 'Тауар жайлы ақпарат',
        'price' => 'Тауардың бағасы',
        'delete' => 'Тауарды бақылаудан алып тастау',
        'help' => 'Нұсқаулық'
    ])->send();
});

Artisan::command('test', function () {
    $scraper = new \App\Services\Scrapers\HalykmarketScraper();
    $r = $scraper->crawlerRequest('https://halykmarket.kz/zhezkazgan/category/vityazhki/vytjazhka-hansa-otp6233bh-chernaja');
    dd($scraper->getPrice());
});

Artisan::command('cron', function () {
    \App\Actions\Scraper\UpsertPriceAllProductScraperAction::execute();
});
