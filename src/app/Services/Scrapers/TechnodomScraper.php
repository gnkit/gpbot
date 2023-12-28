<?php

namespace App\Services\Scrapers;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

final class TechnodomScraper extends BaseScraper
{
    public string $priceElement = '.Typography__Heading_H1';
}
