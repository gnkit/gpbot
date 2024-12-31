<?php

namespace App\Services\Scrapers;

use App\Enums\Store;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class BaseScraper
{
    public Client $client;

    public Crawler $crawler;

    public string $priceElement = '';

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function crawlerRequest($url): ?Crawler
    {
        try {
            $response = $this->client->request('GET', $url);
            $html = $response->getBody()->getContents();

            return $this->crawler = new Crawler($html);

        } catch (ClientException $clientException) {

            Log::error($clientException);
        }
    }

    public function getPrice(): string
    {
        $priceText = $this->crawler->filter($this->priceElement)->text();

        return $this->getOnlyDigits(trim($priceText));
    }

    public function chooseScraper(string $url): ?BaseScraper
    {
        $store = $this->getStore($url);
        $support = $this->checkSupportStore($store);

        if ($support !== null) {
            $scraper = 'App\Services\Scrapers\\'.ucfirst($store).'Scraper';

            return new $scraper();
        }

        return null;
    }

    public function getStore($url): string
    {
        $host = $this->getHost($url);
        $hostParts = explode('.', $host);
        $store = preg_replace(['~[^\pL\s,-]+~us', '~-~'], ['', ''], $hostParts[0]);

        return $store;
    }

    private function getHost($url): string
    {
        return strtolower(str_ireplace('www.', '', parse_url($url, PHP_URL_HOST)));
    }

    protected function getOnlyDigits(string $str): string
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    private function checkSupportStore($store): ?string
    {
        foreach (Store::cases() as $case) {
            if ($store == $case->value) {
                return $case->value;
            }
        }

        return null;
    }
}
