<?php

namespace App\Services\Scrapers;

final class HalykmarketScraper extends BaseScraper
{
    public string $priceElement = '.desc-price-value';
}
