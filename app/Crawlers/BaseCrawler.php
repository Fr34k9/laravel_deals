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

    abstract public function crawlDeals();

    protected function crawl($url, $return = 'body')
    {
        $response = $this->client->request('GET', $url);
        $content = $response->getBody()->getContents();
        if ($return == 'body') {
            return \Str::between($content, '<body>', '</body>');
        }

        return $response->getBody()->getContents();
    }

    protected function prepare_store($deals)
    {
        $res = array();

        foreach( $deals as $deal ) {
            $deal['platform_id'] = $this->config->id;
            $deal['price'] = $this->clean($deal['price']);
            $deal['else_price'] = $this->clean($deal['else_price']);
            $deal['products_total'] = $this->clean_products_total($deal['products_total']);
            $deal['products_left'] = $this->clean_products_total($deal['products_left']);
            $deal['image'] = $this->clean($deal['image']);
            $deal['url'] = $this->clean("www.google.ch");
            $res[] = $deal;
        }

        return $res;
    }

    private function clean_products_total($string)
    {
        return preg_replace('/[^0-9]/', '', $string) ? preg_replace('/[^0-9]/', '', $string) : 0;
    }

    protected function store( $deals ) {
        $deals = $this->prepare_store( $deals );
        foreach( $deals as $deal ) {
            Deal::updateOrCreate(
                ['title' => $deal['title']],
                $deal
            );
        }
    }

    protected function crawlOneDeal($html)
    {
        foreach($this->config->regex as $key => $regex) {
            $deal[$key] = $this->searchRegex($html, $regex);
        }

        return $deal;
    }

    protected function searchRegex($string, $regex)
    {
        if( empty($regex) ) return false;

        preg_match($regex, $string, $matches);
        return $matches[1] ?? null;
    }

    protected function clean($string)
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }
}
