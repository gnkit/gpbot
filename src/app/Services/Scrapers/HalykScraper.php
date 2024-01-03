<?php

namespace App\Services\Scrapers;

final class HalykScraper extends BaseScraper
{
    public string $priceElement = '.desc-price-value';
}
