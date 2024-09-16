<?php

namespace App\Crawlers;

class Twentymin extends BaseCrawler
{
    public function __construct()
    {
        $urls = [
            //'https://myshop.20min.ch/api/proxy/shop/deals',
            'https://myshop.20min.ch/api/proxy/shop/deals?navigation_sections_filter=wochenangebot'
        ];

        $config =  [
            'id' => 6,
            'urls' => $urls,
            'multiple_products' => true, // if the page has multiple products
            'use_proxy' => true,
            'headers' => [
                'Host' => 'myshop.20min.ch',
                'Accept-language' => 'de_DE',
                'Referer' => 'https://myshop.20min.ch/de',
            ],
        ];

        parent::__construct($config);
        $deals = $this->crawlDeals();
        $this->store($deals);
    }

    public function crawlMultipleDeals($html){
        $json = json_decode($html, true);
        $elements = $json['hydra:member'] ?? [];

        $deals = [];
        foreach( $elements as $element ) {
            $deal = [];
            $deal['title'] = $element['title'] ?? '';
            $deal['subtitle'] = $element['homeDescription'] ?? '';
            $deal['price'] = $element['price'] ? $element['price'] / 100 : 0;
            $deal['else_price'] = $element['originalPrice'] ? $element['originalPrice'] / 100 : 0;
            $deal['products_total'] = 100;
            $deal['products_left'] = $element['remainingStockPercent'] ?? 100;
            $deal['image'] = $element['coverPhotoPath'] ?? '';
            $deal['url'] = $element['forthLink'];
            $deal['invalid'] = $element['isSoldOut'] ?? false;
            $deals[] = $deal;
        }

        return $deals;
    }

    
    public function image_prefix()
    {
        return 'https://myshop.20min.ch';
    }
}
