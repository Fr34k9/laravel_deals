<?php

namespace App\Crawlers;

class Digitec extends BaseCrawler
{

    public function __construct()
    {
        $urls = [
            'https://www.digitec.ch/de/daily-deal',
        ];

        $config = [
            'id' => 4,
            'urls' => $urls,
            'multiple_products' => true, // if the page has multiple products
        ];

        parent::__construct($config);
        $deals = $this->crawlDeals();
        $this->store($deals);
    }

    public function crawlMultipleDeals($html)
    {
        preg_match('/<script id="__NEXT_DATA__" type="application\/json" [^>]*>(.*?)<\/script>/s', $html, $matches);
        $json = $matches[1] ?? null;
        if (!$json)
            return [];

        $response = json_decode($json, true);
        $datas = $response['props']['pageProps']['preloadedQuery']['response']['data']['dailyDealProducts'];

        $deals = [];
        foreach ($datas as $data) {
            $data = $data['product'];
            $products_left = intval($data['salesInformation']['numberOfItems']) - intval($data['salesInformation']['numberOfItemsSold']);
            if ($products_left <= 0)
                continue;

            $deal = [];
            $deal['identifier'] = $data['databaseId'];
            $deal['title'] = $data['brand']['name'] . ' ' . $data['name'];
            $deal['subtitle'] = $data['nameExtensions']['properties'] ?? '';
            $deal['price'] = $data['price']['amountInclusive'] ?? 0;
            $deal['else_price'] = $data['insteadOfPrice']['price']['amountInclusive'] ?? 0;
            $deal['products_total'] = $data['salesInformation']['numberOfItems'] ?? 100;
            $deal['products_left'] = $products_left ?? 100;
            $deal['image'] = 'https://static01.galaxus.com/' . $data['previewImages']['nodes'][0]['relativeUrl'] . '_720.avif';
            $deal['url'] = "https://www.galaxus.ch/" . $data['relativeUrl'];
            $deals[] = $deal;
        }

        return $deals;
    }
}
