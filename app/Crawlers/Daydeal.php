<?php

namespace App\Crawlers;

class Daydeal extends BaseCrawler
{

    public function __construct()
    {
        $urls = [
            'https://www.daydeal.ch/de/',
            'https://www.daydeal.ch/de/category/it-multimedia',
            'https://www.daydeal.ch/de/category/haushalt-wohnen',
        ];

        $config =  [
            'id' => 1,
            'urls' => $urls,
            'multiple_products' => false, // if the page has multiple products
            'regex' => [
                'title' => '/<h1 class="ProductMain-Title">([^<]*)<\/h1>/s',
                'subtitle' => '/<h2 class="ProductMain-Subtitle">([^<]*)<\/h2>/s',
                'price' => '/<div class="Price ProductMain-Price".*?<div class=\"Price-Price\">(\d+\.?\d+)\.?.?\<?/s',
                'else_price' => '/<div class="Price ProductMain-Price".*?<div class="Price-OldPriceValue">(\d+\.?\d+)\.?.?\<?/s',
                'products_total' => '',
                'products_left' => '/<div class="ProgressBar ProductMain-ProgressBar">.*?<span class="ProgressBar-TextValue">(\d+)\</s',
                'image' => '/class="ProductMain-Image" *src="([^"]*)" /s',
                'invalid' => ''
            ]
        ];

        parent::__construct($config);
        $deals = $this->crawlDeals();
        $this->store($deals);
    }

    public function crawlDeals()
    {
        $deals = [];
        foreach ($this->config->urls as $url) {
            $body = $this->crawl($url);

            if( $this->config->multiple_products ) {
                $deals[] = $this->crawlMultipleDeals($body, $this->config);
            } else {
                $deals[] = $this->crawlOneDeal($body);
            }
            sleep(1);
        }

        return $deals;
    }


    private function crawlMultipleDeals($html, $config)
    {
        $each_deal_regex = '/<div class="product-item[^>]*>(.*?)<\/div>/is';
        preg_match_all($each_deal_regex, $html, $matches);

        $deals = [];
        foreach ($matches[1] as $deal) {
            $deals[] = [
                'title' => $this->searchRegex($deal, '/<h2 class="product-title">(.+?)<\/h2>/s'),
                'price' => $this->searchRegex($deal, '/<span class="price">(.+?)<\/span>/s'),
                'image' => $this->searchRegex($deal, '/<img src="(.+?)"/s'),
            ];
        }

        return $deals;
    }


}
