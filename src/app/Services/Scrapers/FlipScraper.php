<?php

namespace App\Services\Scrapers;

final class FlipScraper extends BaseScraper
{
    public string $priceElement = '.text_att > span';
}
