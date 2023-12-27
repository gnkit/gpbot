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
    public string $priceElement = '';

    /**
     * @param $url
     * @return Crawler|void
     * @throws GuzzleException
     */
    public function crawlerRequest($url)
    {
        try {
            $newUrl = $this->parseUrlForApi($url);
            $response = $this->client->request('POST', $newUrl, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/119.0',
                    'Accept' => 'application/json, text/*',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Content-Type' => 'application/json; charset=utf-8',
                    'X-KS-City' => $this->cityId,
                    'Origin' => 'https://kaspi.kz',
                    'Connection' => 'keep-alive',
                    'Referer' => 'https://kaspi.kz/shop/p/xiaomi-redmi-smart-band-2-chernyi-109272703/?c=316220100',
                    'Cookie' => "ssaid=604a8450-cb4d-11ed-ad2c-afbf438935cb; ks.tg=13; k_stat=df6092b5-4a1d-4246-8db9-8332669318c7; kaspi.storefront.cookie.city={$this->cityId}; test.user.group=91; test.user.group_exp=47; test.user.group_exp2=20; __tld__=null; .AspNetCore.Culture=c%3Dru%7Cuic%3Dru; NSC_ESNS=2d271f65-dec7-158b-9678-e61af6284ef8_1909451476_4157449272_00000000022232303645",
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'same-origin'
                ],
                'json' => [
                    'cityId' => $this->cityId,
                    'id' => $this->productId,
                ]
            ]);

            $html = $response->getBody()->getContents();
            $this->priceElement = json_decode($html)->offers[0]->price;

            return $this->crawler = new Crawler($html);

        } catch (ClientException $clientException) {

            Log::error($clientException);
        }
    }

    public function price(): string
    {
        return $this->getOnlyDigits(trim($this->priceElement));
    }

    public function parseUrlForApi($url): string
    {
        $urlParsed = parse_url($url);

        $pathUrl = explode('-', basename($urlParsed['path']));
        $pathQuery = explode('=', (isset($urlParsed['query'])) ? $urlParsed['query'] : '');

        $this->cityId = end($pathQuery);
        $this->productId = end($pathUrl);

        return $this->apiPath . $this->productId;
    }

}