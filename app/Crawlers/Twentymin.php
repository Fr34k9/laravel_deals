<?php

declare(strict_types=1);

namespace App\Crawlers;

class Twentymin extends BaseCrawler
{
    public function __construct(?array $config = null)
    {
        $config = array_merge([
            'id' => 6,
            'urls' => [
                'https://myshop.20min.ch/api/proxy/shop/deals?navigation_sections_filter=wochenangebot'
            ],
            'multiple_products' => true,
            'use_proxy' => true,
            'headers' => [
                'Host' => 'myshop.20min.ch',
                'Accept-language' => 'de_DE',
                'Referer' => 'https://myshop.20min.ch/de',
            ],
        ], $config ?? []);

        parent::__construct($config);
    }

    /**
     * @param string $html
     * @return array<int, array<string, mixed>>
     */
    protected function crawlMultipleDeals(string $html): array
    {
        $json = json_decode($html, true);
        $elements = $json['hydra:member'] ?? [];

        $deals = [];
        foreach ($elements as $element) {
            $deals[] = [
                'title' => $element['title'] ?? '',
                'subtitle' => $element['homeDescription'] ?? '',
                'price' => isset($element['price']) ? $element['price'] / 100 : 0,
                'else_price' => isset($element['originalPrice']) ? $element['originalPrice'] / 100 : 0,
                'products_total' => 100,
                'products_left' => $element['remainingStockPercent'] ?? 100,
                'image' => $element['coverPhotoPath'] ?? '',
                'url' => 'https://myshop.20min.ch' . ($element['forthLink'] ?? ''),
                'invalid' => $element['isSoldOut'] ?? false,
            ];
        }

        return $deals;
    }
}
