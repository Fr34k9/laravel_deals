<?php

namespace App\Crawlers;

class Blick extends BaseCrawler implements CrawlableInterface
{
    public function __construct()
    {
        $urls = [
            'https://box.blick.ch/deals',
        ];

        $config = [
            'id' => 2,
            'urls' => $urls,
            'multiple_products' => '/<li class="deals__item[^>]*>(.*?)<\/li>/is', // if the page has multiple products
            'regex' => [
                'title' => '/<span class="deal__name">([^<]*)<\/span>/s',
                'subtitle' => '/deal__name">[^<]*<\/span>[^<]*<\/h2>[^<]*<p class="font-size-h4">([^<]*)<\/p>/s',
                'price' => '/data-dealprice>A?b? ?CHF ([^<]*)<\/span>/s',
                'else_price' => '/data-dealregularprice>CHF ([^<]*)<\/span>/s',
                'products_total' => '',
                'products_left' => '/dealstripe__amount"*>([^<\%]*)\%?<\/span>/s',
                'image' => '/<source type="image\/webp" srcset="[^,]*, ([^ ]*) 2x" /s',
                'url' => '/deal__link--overlay" href="([^"]*)" target/s',
                'invalid' => '/(<h2> ?Aktuell gibt es hier keinen Deal<\/h2>)/s',
                'valid' => '/(<span class="visually-hidden">Brack Logo rot transparent RGB<\/span>)/s'
            ]
        ];

        parent::__construct($config);
        $deals = $this->crawlDeals();
        $this->store($deals);
    }

    public function image_prefix()
    {
        return 'https://box.blick.ch';
    }
}
