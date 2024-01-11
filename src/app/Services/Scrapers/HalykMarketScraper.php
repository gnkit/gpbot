<?php

namespace App\Services\Scrapers;

final class HalykMarketScraper extends BaseScraper
{
    public string $priceElement = '.desc-price-value';
}
