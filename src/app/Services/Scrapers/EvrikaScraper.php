<?php

namespace App\Services\Scrapers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

final class EvrikaScraper extends BaseScraper
{
    private string $apiPath = 'https://back.evrika.com/api/v1/products/';

    private string $productId = '';

    public function crawlerRequest($url): ?Crawler
    {
        try {
            $this->getProductIdForApi($url);
            $response = $this->client->request('GET', $this->apiPath.$this->productId, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:126.0) Gecko/20100101 Firefox/126.0',
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Accept-Encoding' => 'gzip, deflate, br, zstd',
                    'Authorization' => 'Bearer token-key',
                    'Origin' => 'https://evrika.com',
                    'Connection' => 'keep-alive',
                    'Referer' => 'https://evrika.com/',
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'same-site',
                    'TE' => 'trailers',
                ],
                'query' => [
                    'city_id' => '1',
                    'locale' => 'ru',
                ],
            ]);

            $html = $response->getBody()->getContents();

            return $this->crawler = new Crawler($html);

        } catch (ClientException $clientException) {
            Log::error($clientException);

            return null;
        }
    }

    public function getPrice(): string
    {
        $priceText = json_decode($this->crawler->text())->data->cost;

        return $this->getOnlyDigits(trim($priceText));
    }

    private function parseUrlForApi($url): string
    {
        $urlParsed = parse_url($url);

        return basename($urlParsed['path']);
    }

    private function getProductIdForApi($url): void
    {
        $rawProductId = $this->parseUrlForApi($url);
        $productId = $this->getOnlyDigits($rawProductId);

        $this->productId = $productId;
    }
}
