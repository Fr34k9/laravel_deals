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
        preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $html, $matches);
        $json = $matches[1] ?? null;
        if (!$json)
            return [];

        $categories = json_decode($json, true);
        $apolloState = $categories['props']['apolloState'];

        $datas = $this->mergeArrayByProductId($apolloState);

        $deals = [];
        foreach ($datas as $data) {
            $products_left = intval($data['salesInformation']['numberOfItems']) - intval($data['salesInformation']['numberOfItemsSold']);
            if ($products_left <= 0)
                continue;

            $deal = [];
            $deal['identifier'] = $data['productId'];
            $deal['title'] = $data['brandName'] . ' ' . $data['name'];
            $deal['subtitle'] = $data['nameProperties'] ?? '';
            $deal['price'] = $data['price']['amountInclusive'] ?? 0;
            $deal['else_price'] = $data['insteadOfPrice']['price']['amountInclusive'] ?? 0;
            $deal['products_total'] = $data['salesInformation']['numberOfItems'] ?? 100;
            $deal['products_left'] = $products_left ?? 100;
            $deal['image'] = $data['images'][0]['url'];
            $deal['url'] = "https://www.digitec.ch/de/s1/product/" . $data['productId'];
            $deals[] = $deal;
        }

        return $deals;
    }

    private function mergeArrayByProductId(array $originalArray): array
    {
        $mergedArray = [];

        // Iterate through the original array
        foreach ($originalArray as $item) {
            if (empty($item['productId']))
                continue;
            $productId = $item['productId'];

            // If the productId is already present in the merged array, merge the arrays
            if (array_key_exists($productId, $mergedArray)) {
                $mergedArray[$productId] = array_merge($mergedArray[$productId], $item);
            } else {
                // If the productId is not present, simply add the item to the merged array
                $mergedArray[$productId] = $item;
            }
        }

        return $mergedArray;
    }
}
