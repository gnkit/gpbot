<?php

namespace App\Actions\Scraper;

use App\Services\Scrapers\BaseScraper;
use GuzzleHttp\Exception\GuzzleException;

final class GetPriceAction
{
    /**
     * @param $url
     * @return string
     * @throws GuzzleException
     */
    public static function execute($url): string
    {
        $baseScraper = new BaseScraper();
        $scraper = $baseScraper->chooseScraper($url);

        if (null !== $scraper->chooseScraper($url)) {
            $scraper->crawlerRequest($url);

            return $scraper->price();
        }
    }
}
