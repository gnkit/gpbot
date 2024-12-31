<?php

namespace App\Services\Scrapers;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

final class KaspiScraper extends BaseScraper
{
    private string $apiPath = 'https://kaspi.kz/yml/offer-view/offers/';

    private string $productId = '';

    private string $cityId = '';

    /**
     * @throws GuzzleException
     */
    public function crawlerRequest($url): ?Crawler
    {
        try {
            $newUrl = $this->parseUrlForApi($url);
            $response = $this->client->request('POST', $newUrl, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/119.0',
                    'Accept' => 'application/json, text/*',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Accept-Encoding' => 'gzip, deflate, br, zstd',
                    'Content-Type' => 'application/json; charset=utf-8',
                    'X-KS-City' => $this->cityId,
                    'Origin' => 'https://kaspi.kz',
                    'Connection' => 'keep-alive',
                    'Referer' => $url,
                    'Cookie' => 'ks.tg=45; k_stat=7ef2a9b5-35db-4d10-86b2-36af067fe103; ks.cart=c4cf22c3-22af-48ee-8e33-20aa704b752d; locale=ru-RU; current-action-name=Index; kaspi.storefront.cookie.city="$this->cityId"',
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'same-origin',
                ],
                'json' => [
                    'cityId' => $this->cityId,
                    'id' => $this->productId,
                ],
            ]);

            $html = $response->getBody()->getContents();

            return $this->crawler = new Crawler($html);

        } catch (ClientException $clientException) {

            Log::error($clientException);
        }
    }

    public function getPrice(): string
    {
        $priceText = json_decode($this->crawler->text())->offers[0]->price;

        return $this->getOnlyDigits(trim($priceText));
    }

    public function parseUrlForApi($url): string
    {
        $urlParsed = parse_url($url);

        $pathUrl = explode('-', basename($urlParsed['path']));
        $pathQuery = explode('=', (isset($urlParsed['query'])) ? $urlParsed['query'] : '');

        $this->cityId = end($pathQuery);
        $this->productId = end($pathUrl);

        return $this->apiPath.$this->productId;
    }
}
