<?php

declare(strict_types=1);

namespace App\Contracts;

interface CrawlerInterface
{
    /**
     * Crawl deals from the platform.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function crawlDeals(): array;

    /**
     * Store the crawled deals into the database.
     *
     * @param  array<string, array<int, array<string, mixed>>>  $dealsByUrl
     */
    public function store(array $dealsByUrl): void;
}
