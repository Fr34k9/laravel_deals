<?php

namespace App\Crawlers;

class Qoqa extends BaseCrawler
{

    public function __construct()
    {
        $urls = [
            'https://api.qoqa.ch/v2/universes?locale=de',
        ];

        $config =  [
            'id' => 3,
            'urls' => $urls,
            'multiple_products' => true, // if the page has multiple products
        ];

        parent::__construct($config);
        $deals = $this->crawlDeals();
        $this->store($deals);
    }

    public function crawlMultipleDeals($html){
        $categories = json_decode($html);

        $deals = [];
        foreach($categories as $category){
            if(!isset($category->offers) || empty($category->offers)) continue;
            if(strpos($category->website_tracking_id, 'qwine') !== false) continue;

            foreach($category->offers as $offer ) {
                $deal = [];
                $deal['title'] = $offer->title;
                $deal['subtitle'] = $offer->subtitle;
                $deal['price'] = $offer->offer_price_text;
                $deal['else_price'] = $offer->best_price_text;
                $deal['products_total'] = 100;
                $deal['products_left'] = $offer->remaining_stock_percent;
                $deal['image'] = $offer->image_urls->standard->url;
                $deal['url'] = $offer->url;
                $deals[] = $deal;
            }
        }

        return $deals;
    }

    public function clean_price($price)
    {
        $price = str_replace('’', '', $price);
        $price = str_replace('.-', '', $price);

        // Convert "Ab 52.– bis 169.-" to 169.00
        if (strpos($price, 'bis') !== false) {
            $price = explode('bis', $price)[1];
        }

        $price = floatval($price);
        return $price;
    }
}
