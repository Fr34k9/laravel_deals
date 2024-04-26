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
}
