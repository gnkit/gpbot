<?php

namespace App\Actions\Scraper;

use App\Services\Scrapers\BaseScraper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class CheckUrlAction
{
    /**
     * @param $url
     * @return int|string
     * @throws GuzzleException
     */
    public static function execute($url): int|string
    {
        $validUrl = filter_var($url, FILTER_VALIDATE_URL);
        $supportedStore = ((new BaseScraper)->chooseScraper($url)) instanceof BaseScraper;

        if (false === $validUrl) {

            return 'Хабарламаңыз сілтеме емес.';

        } elseif (true !== $supportedStore) {

            return 'Әзірше бұл дүкеннің сілтемелері қолдауда жоқ.';

        } else {

            $client = new Client();
            $response = $client->get($url, ['timeout' => 1]);

            return $response->getStatusCode();
        }
    }
}
