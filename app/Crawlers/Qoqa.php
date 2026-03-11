<?php

declare(strict_types=1);

namespace App\Crawlers;

use App\Data\DealData;

class Qoqa extends BaseCrawler
{
    public function __construct(?array $config = null)
    {
        $config = array_merge([
            'id' => 3,
            'urls' => [
                'https://api.qoqa.ch/v2/universes?locale=de',
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
        $categories = json_decode($html, false);
        if (!$categories) {
            return [];
        }

        $deals = [];
        foreach ($categories as $category) {
            if (!isset($category->offers) || empty($category->offers)) {
                continue;
            }
            if (str_contains($category->website_tracking_id ?? '', 'qwine')) {
                continue;
            }

            foreach ($category->offers as $offer) {
                $deals[] = new DealData(
                    platform_id: $this->config->id ?? null,
                    title: $offer->title ?? '',
                    subtitle: $offer->subtitle ?? '',
                    price: $this->clean_price($offer->offer_price_text ?? '0'),
                    else_price: $this->clean_price($offer->best_price_text ?? '0'),
                    products_total: 100,
                    products_left: $offer->remaining_stock_percent ?? 100,
                    image: $offer->image_urls->standard->url ?? '',
                    url: $offer->url ?? '',
                );
            }
        }

        return $deals;
    }

    public function clean_price(string|float|int $price): float
    {
        if (is_float($price) || is_int($price)) {
            return (float) $price;
        }

        $price = str_replace(["\u{2019}", '.-', '.–'], '', $price);

        // Convert "Ab 52.– bis 169.-" to 169.00
        if (str_contains($price, 'bis')) {
            $parts = explode('bis', $price);
            $price = end($parts);
        }

        return (float) filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
