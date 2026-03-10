<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\CrawlerInterface;
use App\Crawlers\Daydeal;
use App\Crawlers\Digitec;
use App\Crawlers\Twentymin;
use App\Models\Platform;
use App\Services\CrawlerFactory;
use InvalidArgumentException;
use Tests\TestCase;

class CrawlerFactoryTest extends TestCase
{
    /**
     * Test that the factory creates the correct crawler for a given platform.
     */
    public function test_it_creates_correct_crawler_from_mapping(): void
    {
        $factory = new CrawlerFactory();
        
        $platform = new Platform(['name' => '20min']);
        $crawler = $factory->make($platform);
        $this->assertInstanceOf(Twentymin::class, $crawler);
        $this->assertInstanceOf(CrawlerInterface::class, $crawler);

        $platform = new Platform(['name' => 'Digitec']);
        $crawler = $factory->make($platform);
        $this->assertInstanceOf(Digitec::class, $crawler);
    }

    /**
     * Test that the factory uses fallback resolution for unmapped platforms.
     */
    public function test_it_uses_fallback_resolution(): void
    {
        $factory = new CrawlerFactory();
        
        // This is not in the mapping, but the class App\Crawlers\Daydeal exists
        $platform = new Platform(['name' => 'daydeal']);
        $crawler = $factory->make($platform);
        
        $this->assertInstanceOf(Daydeal::class, $crawler);
    }

    /**
     * Test that the factory throws an exception for non-existent crawlers.
     */
    public function test_it_throws_exception_for_unknown_platform(): void
    {
        $factory = new CrawlerFactory();
        
        $platform = new Platform(['name' => 'UnknownPlatform']);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Crawler not found for platform: UnknownPlatform');
        
        $factory->make($platform);
    }
}
