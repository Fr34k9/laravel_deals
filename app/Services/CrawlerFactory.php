<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\CrawlerInterface;
use App\Crawlers\Blick;
use App\Crawlers\Daydeal;
use App\Crawlers\Digitec;
use App\Crawlers\Galaxus;
use App\Crawlers\Qoqa;
use App\Crawlers\Twentymin;
use App\Models\Platform;
use InvalidArgumentException;

class CrawlerFactory
{
    /**
     * Mapping of platform names to their respective crawler classes.
     *
     * @var array<string, string>
     */
    private const CRAWLER_MAPPING = [
        '20min' => Twentymin::class,
        'blick' => Blick::class,
        'daydeal' => Daydeal::class,
        'digitec' => Digitec::class,
        'galaxus' => Galaxus::class,
        'qoqa' => Qoqa::class,
    ];

    /**
     * Make a crawler instance for the given platform.
     *
     *
     * @throws InvalidArgumentException
     */
    public function make(Platform $platform): CrawlerInterface
    {
        $platformName = strtolower($platform->name);

        $className = self::CRAWLER_MAPPING[$platformName] ?? null;

        if (! $className || ! class_exists($className)) {
            // Fallback to ucfirst name in case it's not in the mapping but exists
            $fallbackClass = 'App\\Crawlers\\'.ucfirst($platformName);
            $className = class_exists($fallbackClass) ? $fallbackClass : null;
        }

        if (! $className) {
            throw new InvalidArgumentException("Crawler not found for platform: {$platform->name}");
        }

        /** @var CrawlerInterface $crawler */
        $crawler = new $className($platform->toArray());

        if (! $crawler instanceof CrawlerInterface) {
            throw new InvalidArgumentException("Class {$className} must implement CrawlerInterface");
        }

        return $crawler;
    }
}
