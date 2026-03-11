<?php

declare(strict_types=1);

namespace Tests\Unit\Crawlers;

use App\Crawlers\Digitec;
use App\Crawlers\Galaxus;
use App\Crawlers\Qoqa;
use App\Crawlers\Twentymin;
use App\Data\DealData;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChildCrawlerTest extends TestCase
{
    private function fakeDigitecHtml(array $products): string
    {
        $json = json_encode([
            'props' => [
                'pageProps' => [
                    'preloadedQuery' => [
                        'rawResponse' => [
                            'data' => [
                                'dailyDealProducts' => $products,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return '<body><script id="__NEXT_DATA__" type="application/json" crossorigin="anonymous">' . $json . '</script></body>';
    }

    public function test_digitec_returns_deal_data_objects(): void
    {
        $html = $this->fakeDigitecHtml([
            [
                'product' => [
                    'brand' => ['name' => 'Samsung'],
                    'name' => 'Galaxy S25',
                    'nameExtensions' => ['properties' => '128GB Black'],
                    'price' => ['amountInclusive' => 799.0],
                    'insteadOfPrice' => ['price' => ['amountInclusive' => 999.0]],
                    'salesInformation' => ['numberOfItems' => 500, 'numberOfItemsSold' => 200],
                    'previewImages' => ['nodes' => [['relativeUrl' => 'img/samsung-s25']]],
                    'relativeUrl' => 'en/product/samsung-galaxy-s25',
                ],
            ],
        ]);

        Http::fake([
            'https://www.digitec.ch/*' => Http::response($html),
        ]);

        $crawler = new Digitec();
        $deals = $crawler->crawlDeals();

        $this->assertArrayHasKey('https://www.digitec.ch/de/daily-deal', $deals);
        $dealsForUrl = $deals['https://www.digitec.ch/de/daily-deal'];
        $this->assertCount(1, $dealsForUrl);
        $this->assertInstanceOf(DealData::class, $dealsForUrl[0]);
        $this->assertEquals('Samsung Galaxy S25', $dealsForUrl[0]->title);
        $this->assertEquals('128GB Black', $dealsForUrl[0]->subtitle);
        $this->assertEquals(799.0, $dealsForUrl[0]->price);
        $this->assertEquals(999.0, $dealsForUrl[0]->else_price);
        $this->assertEquals(500, $dealsForUrl[0]->products_total);
        $this->assertEquals(300, $dealsForUrl[0]->products_left);
        $this->assertEquals(4, $dealsForUrl[0]->platform_id);
    }

    public function test_digitec_skips_sold_out_products(): void
    {
        $html = $this->fakeDigitecHtml([
            [
                'product' => [
                    'brand' => ['name' => 'Apple'],
                    'name' => 'iPhone 15',
                    'nameExtensions' => ['properties' => '256GB'],
                    'price' => ['amountInclusive' => 899.0],
                    'insteadOfPrice' => ['price' => ['amountInclusive' => 1099.0]],
                    'salesInformation' => ['numberOfItems' => 100, 'numberOfItemsSold' => 100],
                    'previewImages' => ['nodes' => [['relativeUrl' => 'img/iphone']]],
                    'relativeUrl' => 'en/product/iphone-15',
                ],
            ],
        ]);

        Http::fake([
            'https://www.digitec.ch/*' => Http::response($html),
        ]);

        $crawler = new Digitec();
        $deals = $crawler->crawlDeals();

        $this->assertEmpty($deals['https://www.digitec.ch/de/daily-deal'] ?? []);
    }

    public function test_galaxus_returns_deal_data_objects(): void
    {
        $html = $this->fakeDigitecHtml([
            [
                'product' => [
                    'brand' => ['name' => 'Sony'],
                    'name' => 'WH-1000XM5',
                    'nameExtensions' => ['properties' => 'Noise Cancelling'],
                    'price' => ['amountInclusive' => 299.0],
                    'insteadOfPrice' => ['price' => ['amountInclusive' => 399.0]],
                    'salesInformation' => ['numberOfItems' => 200, 'numberOfItemsSold' => 50],
                    'previewImages' => ['nodes' => [['relativeUrl' => 'img/sony-xm5']]],
                    'relativeUrl' => 'en/product/sony-wh1000xm5',
                ],
            ],
        ]);

        Http::fake([
            'https://www.galaxus.ch/*' => Http::response($html),
        ]);

        $crawler = new Galaxus();
        $deals = $crawler->crawlDeals();

        $this->assertArrayHasKey('https://www.galaxus.ch/de/daily-deal', $deals);
        $dealsForUrl = $deals['https://www.galaxus.ch/de/daily-deal'];
        $this->assertCount(1, $dealsForUrl);
        $this->assertInstanceOf(DealData::class, $dealsForUrl[0]);
        $this->assertEquals('Sony WH-1000XM5', $dealsForUrl[0]->title);
        $this->assertEquals(299.0, $dealsForUrl[0]->price);
        $this->assertEquals(150, $dealsForUrl[0]->products_left);
        $this->assertEquals(5, $dealsForUrl[0]->platform_id);
    }

    public function test_qoqa_returns_deal_data_objects(): void
    {
        $jsonBody = json_encode([
            (object) [
                'website_tracking_id' => 'qoqa-main',
                'offers' => [
                    (object) [
                        'title' => 'Robot Vacuum',
                        'subtitle' => 'Roborock S8',
                        'offer_price_text' => '399.-',
                        'best_price_text' => '599.-',
                        'remaining_stock_percent' => 65,
                        'image_urls' => (object) [
                            'standard' => (object) ['url' => 'https://qoqa.ch/img/vacuum.jpg'],
                        ],
                        'url' => 'https://qoqa.ch/deal/vacuum',
                    ],
                ],
            ],
        ]);

        Http::fake([
            'https://api.qoqa.ch/*' => Http::response('<body>' . $jsonBody . '</body>'),
        ]);

        $crawler = new Qoqa();
        $deals = $crawler->crawlDeals();

        $this->assertArrayHasKey('https://api.qoqa.ch/v2/universes?locale=de', $deals);
        $dealsForUrl = $deals['https://api.qoqa.ch/v2/universes?locale=de'];
        $this->assertCount(1, $dealsForUrl);
        $this->assertInstanceOf(DealData::class, $dealsForUrl[0]);
        $this->assertEquals('Robot Vacuum', $dealsForUrl[0]->title);
        $this->assertEquals('Roborock S8', $dealsForUrl[0]->subtitle);
        $this->assertEquals(65, $dealsForUrl[0]->products_left);
        $this->assertEquals(3, $dealsForUrl[0]->platform_id);
    }

    public function test_qoqa_skips_wine_categories(): void
    {
        $jsonBody = json_encode([
            (object) [
                'website_tracking_id' => 'qwine-main',
                'offers' => [
                    (object) [
                        'title' => 'Wine Deal',
                        'subtitle' => 'Red Wine',
                        'offer_price_text' => '29.-',
                        'best_price_text' => '49.-',
                        'remaining_stock_percent' => 80,
                        'image_urls' => (object) [
                            'standard' => (object) ['url' => 'https://qoqa.ch/img/wine.jpg'],
                        ],
                        'url' => 'https://qoqa.ch/deal/wine',
                    ],
                ],
            ],
        ]);

        Http::fake([
            'https://api.qoqa.ch/*' => Http::response('<body>' . $jsonBody . '</body>'),
        ]);

        $crawler = new Qoqa();
        $deals = $crawler->crawlDeals();

        $this->assertEmpty($deals['https://api.qoqa.ch/v2/universes?locale=de'] ?? []);
    }

    public function test_twentymin_returns_deal_data_objects(): void
    {
        $jsonBody = json_encode([
            'hydra:member' => [
                [
                    'title' => 'Bluetooth Speaker',
                    'homeDescription' => 'JBL Flip 6',
                    'price' => 7990,
                    'originalPrice' => 12900,
                    'remainingStockPercent' => 42,
                    'coverPhotoPath' => 'https://20min.ch/img/speaker.jpg',
                    'forthLink' => '/deals/speaker',
                    'isSoldOut' => false,
                ],
            ],
        ]);

        Http::fake([
            'https://myshop.20min.ch/*' => Http::response('<body>' . $jsonBody . '</body>'),
        ]);

        $crawler = new Twentymin();
        $deals = $crawler->crawlDeals();

        $this->assertArrayHasKey('https://myshop.20min.ch/api/proxy/shop/deals?navigation_sections_filter=wochenangebot', $deals);
        $dealsForUrl = $deals['https://myshop.20min.ch/api/proxy/shop/deals?navigation_sections_filter=wochenangebot'];
        $this->assertCount(1, $dealsForUrl);
        $this->assertInstanceOf(DealData::class, $dealsForUrl[0]);
        $this->assertEquals('Bluetooth Speaker', $dealsForUrl[0]->title);
        $this->assertEquals('JBL Flip 6', $dealsForUrl[0]->subtitle);
        $this->assertEquals(79.90, $dealsForUrl[0]->price);
        $this->assertEquals(129.0, $dealsForUrl[0]->else_price);
        $this->assertEquals(42, $dealsForUrl[0]->products_left);
        $this->assertFalse($dealsForUrl[0]->invalid);
        $this->assertEquals(6, $dealsForUrl[0]->platform_id);
    }

    public function test_twentymin_marks_sold_out_deals(): void
    {
        $jsonBody = json_encode([
            'hydra:member' => [
                [
                    'title' => 'Sold Out Item',
                    'homeDescription' => 'Gone',
                    'price' => 5000,
                    'originalPrice' => 10000,
                    'remainingStockPercent' => 0,
                    'coverPhotoPath' => 'https://20min.ch/img/gone.jpg',
                    'forthLink' => '/deals/gone',
                    'isSoldOut' => true,
                ],
            ],
        ]);

        Http::fake([
            'https://myshop.20min.ch/*' => Http::response('<body>' . $jsonBody . '</body>'),
        ]);

        $crawler = new Twentymin();
        $deals = $crawler->crawlDeals();

        $dealsForUrl = $deals['https://myshop.20min.ch/api/proxy/shop/deals?navigation_sections_filter=wochenangebot'];
        $this->assertInstanceOf(DealData::class, $dealsForUrl[0]);
        $this->assertTrue($dealsForUrl[0]->invalid);
        $this->assertEquals(0, $dealsForUrl[0]->products_left);
    }
}
