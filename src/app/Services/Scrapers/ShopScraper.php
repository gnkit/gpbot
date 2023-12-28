<?php

namespace App\Services\Scrapers;

final class ShopScraper extends BaseScraper
{
    public string $priceElement = '.item_current_price';
}
