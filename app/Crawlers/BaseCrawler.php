<?php

namespace App\Crawlers;
use App\Models\Deal;
use GuzzleHttp\Client;

abstract class BaseCrawler
{
    protected $client;
    public $config;

    public function __construct($config = null)
    {
        $this->client = new Client();
        $this->config = (object) $config;
    }

    // abstract public function crawlDeals();

    protected function crawl($url, $return = 'body')
    {
        $response = $this->client->request('GET', $url);
        $content = $response->getBody()->getContents();
        if ($return == 'body') {
            return \Str::between($content, '<body>', '</body>');
        }

        return $response->getBody()->getContents();
    }

    protected function prepare_store($urls)
    {
        $res = [];
        foreach($urls as $url => $deals) {
            foreach( $deals as $deal ) {
                $tmp = [];
                if( isset( $deal['invalid'] ) && !empty( $deal['invalid'] ) ) continue;
                if( isset( $deal['valid'] ) && empty( $deal['valid'] ) ) continue;

                $tmp['platform_id'] = $this->config->id;
                $tmp['title'] = $this->clean($deal['title']);
                $tmp['subtitle'] = $this->clean($deal['subtitle']);
                $tmp['price'] = $this->clean_price($deal['price']);
                $tmp['else_price'] = $this->clean_price($deal['else_price']);
                $tmp['products_total'] = $this->clean_product_count_total($deal['products_total']);
                $tmp['products_left'] = $this->clean_product_count_left($deal['products_left']);
                $tmp['image'] = $this->image_prefix() . $this->clean($deal['image']);
                $tmp['url'] = $url;
                $tmp['updated_at'] = now();
                $res[$url][] = $tmp;
            }
        }

        return $res;
    }

    public function image_prefix()
    {
        return '';
    }

    private function clean_price($string)
    {
        // convert 1&#039;599.–to 1599.00
        $string = str_replace('&#039;', '', $string);

        $string = str_replace('\'', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace('CHF', '', $string);

        $int = floatval( $string );

        return $int;
    }

    private function clean_product_count_total($string)
    {
        return preg_replace('/[^0-9]/', '', $string) ? preg_replace('/[^0-9]/', '', $string) : 100;
    }

    private function clean_product_count_left($string)
    {
        return preg_replace('/[^0-9]/', '', $string) ? preg_replace('/[^0-9]/', '', $string) : 0;
    }

    protected function store( $urls ) {
        $urls = $this->prepare_store( $urls );

        foreach( $urls as $deals ) {
            foreach( $deals as $deal ) {
                Deal::updateOrCreate(
                    ['title' => $deal['title']],
                    $deal
                );
                echo "Updated";
            }
        }
    }

    protected function searchRegex($string, $regex)
    {
        if( empty($regex) ) return false;

        preg_match($regex, $string, $matches);
        return $matches[1] ?? false;
    }

    protected function clean($string)
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    protected function crawlDeals()
    {
        $deals = [];
        foreach ($this->config->urls as $url) {
            $body = $this->crawl($url);

            if( $this->config->multiple_products ) {
                $deals[$url] = $this->crawlMultipleDeals($body);
            } else {
                $deals[$url] = $this->crawlOneDeal($body);
            }
            sleep(1);
        }

        return $deals;
    }


    protected function crawlOneDeal($html)
    {
        $deals = [];
        $deal = [];
        foreach($this->config->regex as $key => $regex) {
            $deal[$key] = $this->searchRegex($html, $regex);
        }
        $deals[] = $deal;

        return $deals;
    }

    protected function crawlMultipleDeals($html)
    {
        preg_match_all($this->config->multiple_products, $html, $matches);

        $deals = [];
        foreach ($matches[1] as $deal_html) {
            $deal = [];
            foreach($this->config->regex as $key => $regex) {
                $deal[$key] = $this->searchRegex($deal_html, $regex);
            }
            $deals[] = $deal;
        }

        return $deals;
    }
}
