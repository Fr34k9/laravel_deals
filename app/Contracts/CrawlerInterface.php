<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\DealData;

interface CrawlerInterface
{
    /**
     * Crawl deals from the platform.
     *
     * @return array<string, DealData[]>
     */
    public function crawlDeals(): array;

    /**
     * Store the crawled deals into the database.
     *
     * @param  array<string, DealData[]>  $dealsByUrl
     */
    public function store(array $dealsByUrl): void;
}
