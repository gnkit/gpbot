<?php

namespace App\Services\Scrapers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

final class MechtaScraper extends BaseScraper
{
    private string $apiPathForId = 'https://www.mechta.kz/api/v1/product/';
    private string $apiPath = 'https://www.mechta.kz/api/v1/mindbox/actions/product';
    private string $productId = '';

    public function crawlerRequest($url): ?Crawler
    {
        try {
            $this->getProductIdForApi($url);
            $response = $this->client->request('POST', $this->apiPath, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/119.0',
                    'Accept' => 'application/json, text/plain, */*',
                    'Accept-Language' => 'ru',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'x-city-code' => 's1',
                    'X-Mechta-App' => 'site',
                    'X-XSRF-TOKEN' => 'eyJpdiI6InBkWUtySHlMQkVHRmxrZHB0SFNzcGc9PSIsInZhbHVlIjoiL0hOZkFwSjdwVzE0NllvalNXdnF5SzJKMko3SXBzQi9KN1FCeWYyRXNIZGZGT0NpWStNd1dqL3BFL2ZCeWFneDV0YmJkV1FRZ29OZFJSU3VOdXl4SjF1VUU3dDEzN20rdWhKL3VFU0laY0pudXM1T2N0QzlPZ3o1Ly9YUndOZVEiLCJtYWMiOiI5ZDUxODBiMDhiZDhiMjI3Yzg2MTZiNDYzMDA5NDRjNzBlNTdjMThmZDk0OTZmYzZlZDNmYWUxNjhhZmQ5NzA1IiwidGFnIjoiIn0=',
                    'Content-Type' => 'multipart/form-data; boundary=---------------------------227290112818721773762037688088',
                    'Origin' => 'https://www.mechta.kz',
                    'Connection' => 'keep-alive',
                    'Referer' => $url,
                    'Cookie' => 'amp_468712=AOLdBB32PrCl1eH0TswRls...1hinrdt7j.1hinrhe1p.2.1.3; _userGUID=0:llqt7bu2:igAqBVZGjb9LCMOjHQ9cyTtvfmD4NiP7; digi_uc=W1sidiIsImVlMTFmNGI3LWU0YjgtMTFlZC1hMjVhLTAwNTA1NmI2ZGJkNyIsMTcwMzc1NTEzNjIwMl1d; _ms=01471329-9241-4970-aa45-baa75288cd79; is_search=1; mechtakz_session=SqMQHzVseheUH2kyZDNlNTGBQBTmTpCR2fTPXwcz; XSRF-TOKEN=eyJpdiI6InBkWUtySHlMQkVHRmxrZHB0SFNzcGc9PSIsInZhbHVlIjoiL0hOZkFwSjdwVzE0NllvalNXdnF5SzJKMko3SXBzQi9KN1FCeWYyRXNIZGZGT0NpWStNd1dqL3BFL2ZCeWFneDV0YmJkV1FRZ29OZFJSU3VOdXl4SjF1VUU3dDEzN20rdWhKL3VFU0laY0pudXM1T2N0QzlPZ3o1Ly9YUndOZVEiLCJtYWMiOiI5ZDUxODBiMDhiZDhiMjI3Yzg2MTZiNDYzMDA5NDRjNzBlNTdjMThmZDk0OTZmYzZlZDNmYWUxNjhhZmQ5NzA1IiwidGFnIjoiIn0%3D; dSesn=17dc54db-83fe-9bcb-ad69-459721dff4e5; _dvs=0:lqozrobs:4a3o6wsQydFTtgVPZ8RtdxEa6yFHb9hl; selected_action=eyJpdiI6IkpmZzQxZGxNQ2FzOUFQUFM0MWZVL2c9PSIsInZhbHVlIjoiRnFnaVNxdHl4VGgzdUF2SXZpLy9VdDVkS1pZZlFCNUhFbjhxemtjVVpjYitTVi9mejQ5UERPVFpFYndVQm9VOCIsIm1hYyI6ImVhNDExMzgzY2RhNzg4ZmU3Y2IxZWY5ZjRmZTg4ZDUxYzM3YTg0ZWRiNjU0ZTVlZDk4YmQ1NzY2YTU5MDkzN2QiLCJ0YWciOiIifQ%3D%3D; applied_coupons=eyJpdiI6IkhwUEtXVTFTcm9JVkpOMXVsRy94a3c9PSIsInZhbHVlIjoiQUdCdnp0eUExeW5NZnFUMGE4TkxvY2praXk4SUpISitqRm9JY2daeGNjdi9PVzZWM1EzdFFNUVloeHJtcElwRCIsIm1hYyI6IjFmN2MzYzkwMmVlY2M4MjEyNzU0ZjJjMGQ4MGIwZDU2NDk1ZDM0MTQ2NDNmYjljZTk3MTk2MDQwMjZiOGIwODEiLCJ0YWciOiIifQ%3D%3D',
                    'Sec-Fetch-Dest' => 'empty',
                    'Sec-Fetch-Mode' => 'cors',
                    'Sec-Fetch-Site' => 'same-origin',
                    'TE' => 'trailers'
                ],
                'body' => "-----------------------------227290112818721773762037688088\r\nContent-Disposition: form-data; name=\"product_ids\"\r\n\r\n{$this->productId}\r\n-----------------------------227290112818721773762037688088--\r\n"
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
        $priceText = json_decode($this->crawler->text())->data->prices->discounted_price;

        return $this->getOnlyDigits(trim($priceText));
    }

    private function parseUrlForApi($url): string
    {
        $urlParsed = parse_url($url);

        return $this->apiPathForId . basename($urlParsed['path']);
    }

    private function getProductIdForApi($url): void
    {
        $newUrl = $this->parseUrlForApi($url);
        $responseForId = $this->client->request('GET', $newUrl);
        $html = $responseForId->getBody()->getContents();
        $this->productId = (json_decode($html))->data->id;
    }
}
