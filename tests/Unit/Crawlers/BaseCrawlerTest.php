<?php

declare(strict_types=1);

namespace Tests\Unit\Crawlers;

use App\Crawlers\BaseCrawler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BaseCrawlerTest extends TestCase
{
    /**
     * Create a concrete test crawler that extends BaseCrawler.
     */
    private function makeCrawler(array $config, int $retries = 1): BaseCrawler
    {
        return new class($config, $retries) extends BaseCrawler {
            public function __construct(array $config, int $retries)
            {
                parent::__construct($config);
                $this->retries = $retries;
            }
        };
    }

    public function test_crawl_continues_to_next_url_after_all_retries_fail(): void
    {
        Http::fake([
            'https://fail.example.com/*' => Http::sequence()
                ->push(fn () => throw new \Exception('Proxy error'), 500)
                ->push(fn () => throw new \Exception('Proxy error'), 500),
            'https://ok.example.com/*' => Http::response('<body><h1>Test Deal</h1></body>'),
        ]);

        Log::shouldReceive('warning')->once();
        Log::shouldReceive('error')->once();

        $crawler = $this->makeCrawler([
            'urls' => ['https://fail.example.com/', 'https://ok.example.com/'],
            'regex' => ['title' => '/<h1>([^<]*)<\/h1>/s'],
        ]);

        $deals = $crawler->crawlDeals();

        // The failed URL should be skipped, successful URL should have deals
        $this->assertArrayNotHasKey('https://fail.example.com/', $deals);
        $this->assertArrayHasKey('https://ok.example.com/', $deals);
        $this->assertEquals('Test Deal', $deals['https://ok.example.com/'][0]['title']);
    }

    public function test_crawl_succeeds_on_retry(): void
    {
        Http::fake([
            'https://retry.example.com/*' => Http::sequence()
                ->push(fn () => throw new \Exception('Proxy error'), 500)
                ->push('<body><h1>Recovered Deal</h1></body>'),
        ]);

        Log::shouldReceive('warning')->once();
        Log::shouldReceive('error')->never();

        $crawler = $this->makeCrawler([
            'urls' => ['https://retry.example.com/'],
            'regex' => ['title' => '/<h1>([^<]*)<\/h1>/s'],
        ]);

        $deals = $crawler->crawlDeals();

        $this->assertArrayHasKey('https://retry.example.com/', $deals);
        $this->assertEquals('Recovered Deal', $deals['https://retry.example.com/'][0]['title']);
    }

    public function test_retries_property_controls_retry_count(): void
    {
        Http::fake([
            'https://multi-retry.example.com/*' => Http::sequence()
                ->push(fn () => throw new \Exception('Fail 1'), 500)
                ->push(fn () => throw new \Exception('Fail 2'), 500)
                ->push('<body><h1>Third Try</h1></body>'),
        ]);

        Log::shouldReceive('warning')->twice();
        Log::shouldReceive('error')->never();

        $crawler = $this->makeCrawler([
            'urls' => ['https://multi-retry.example.com/'],
            'regex' => ['title' => '/<h1>([^<]*)<\/h1>/s'],
        ], retries: 2);

        $deals = $crawler->crawlDeals();

        $this->assertArrayHasKey('https://multi-retry.example.com/', $deals);
        $this->assertEquals('Third Try', $deals['https://multi-retry.example.com/'][0]['title']);
    }
}
