<?php

namespace App\Actions\Scraper;

use App\Services\Scrapers\BaseScraper;
use GuzzleHttp\Exception\GuzzleException;

final class GetPriceScraperAction
{
    /**
     * @param $url
     * @return string|null
     * @throws GuzzleException
     */
    public static function execute($url): ?string
    {
        $baseScraper = new BaseScraper();
        $scraper = $baseScraper->chooseScraper($url);

        if (null !== $scraper) {
            $scraper->crawlerRequest($url);

            return $scraper->getPrice();
        } else {

            return null;
        }
    }
}
