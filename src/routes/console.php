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
    $scraper = new \App\Services\Scrapers\KaspiScraper();
    $r = $scraper->crawlerRequest('https://kaspi.kz/shop/p/shlang-garden-hose-22-5-m-109810467/?c=316220100');
//    $r = $scraper->crawlerRequest('https://www.technodom.kz/p/morozilnik-ava-cfr-300w-276422?recommended_by=dynamic&recommended_code=z9wxnh4hkr');
//    $r = $scraper->crawlerRequest('https://www.sulpak.kz/g/noutbuki_acer_aspire_3_a315_59__nxk6ter009__i582sun/karatau');
//    $r = $scraper->crawlerRequest('https://shop.kz/offer/tochka-dostupa-mikrotik-rbomnitikpg-5hacd/');
//    $r = $scraper->crawlerRequest('https://www.mechta.kz/product/kondicioner-midea-msag-07hrn1-outdoor-unit/');
//    $r = $scraper->crawlerRequest('https://halykmarket.kz/category/mineralnaja-vata/isover-warm-roofs-master-50600x1000-8-sht-');
//    $r = $scraper->crawlerRequest('https://www.flip.kz/catalog?prod=4242441');
//    $r = $scraper->crawlerRequest('https://evrika.com/catalog/holodilnik-lg-gc-b459mlwm/p40437');
//    $r = $scraper->crawlerRequest('https://alser.kz/p/jelektricheskaja-poverhnost-samsung-nz63f3nm1ab');
//dd($r);
    dd($scraper->getPrice());
});

Artisan::command('cron', function () {
    \App\Actions\Scraper\UpsertPriceAllProductScraperAction::execute();
});
