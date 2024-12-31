<?php

namespace App\Services\Scrapers;

final class AlserScraper extends BaseScraper
{
    public string $priceElement = '.price-container div span.price';
}
