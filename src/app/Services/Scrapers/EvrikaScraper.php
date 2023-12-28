<?php

namespace App\Services\Scrapers;

final class EvrikaScraper extends BaseScraper
{
    public string $priceElement = '.cost__value';
}
