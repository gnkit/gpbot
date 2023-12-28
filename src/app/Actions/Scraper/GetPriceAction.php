<?php

namespace App\Actions\Scraper;

use App\Services\Scrapers\BaseScraper;
use GuzzleHttp\Exception\GuzzleException;

final class GetPriceAction
{
    /**
     * @param $url
     * @return string|null
     * @throws GuzzleException
     */
    public static function execute($url): string|null
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
