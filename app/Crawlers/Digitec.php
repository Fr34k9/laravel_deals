<?php

declare(strict_types=1);

namespace App\Crawlers;

use App\Data\DealData;

class Digitec extends BaseCrawler
{
    public function __construct(?array $config = null)
    {
        $config = array_merge([
            'id' => 4,
            'urls' => [
                'https://www.digitec.ch/de/daily-deal',
            ],
            'multiple_products' => true,
        ], $config ?? []);

        parent::__construct($config);
    }

    /**
     * @param string $html
     * @return DealData[]
     */
    protected function crawlMultipleDeals(string $html): array
    {
        preg_match('/<script id="__NEXT_DATA__" type="application\/json" [^>]*>(.*?)<\/script>/s', $html, $matches);
        $json = $matches[1] ?? null;
        if (!$json) {
            return [];
        }

        $response = json_decode($json, true);
        $dailyDealProducts = $response['props']['pageProps']['preloadedQuery']['rawResponse']['data']['dailyDealProducts'] ?? [];

        $deals = [];
        foreach ($dailyDealProducts as $item) {
            $data = $item['product'] ?? null;
            if (!$data) {
                continue;
            }

            $productsTotal = (int) ($data['salesInformation']['numberOfItems'] ?? 100);
            $productsSold = (int) ($data['salesInformation']['numberOfItemsSold'] ?? 0);
            $productsLeft = $productsTotal - $productsSold;

            if ($productsLeft <= 0) {
                continue;
            }

            $deals[] = new DealData(
                platform_id: $this->config->id ?? null,
                title: ($data['brand']['name'] ?? '') . ' ' . ($data['name'] ?? ''),
                subtitle: $data['nameExtensions']['properties'] ?? '',
                price: (float) ($data['price']['amountInclusive'] ?? 0),
                else_price: (float) ($data['insteadOfPrice']['price']['amountInclusive'] ?? 0),
                products_total: $productsTotal,
                products_left: $productsLeft,
                image: 'https://static01.galaxus.com/' . ($data['previewImages']['nodes'][0]['relativeUrl'] ?? '') . '_720.avif',
                url: "https://www.galaxus.ch/" . ($data['relativeUrl'] ?? ''),
            );
        }

        return $deals;
    }
}
