<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CrawlJob;
use App\Models\Platform;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Crawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:start {platform?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts all crawlers';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $platformArgument = $this->argument('platform');

        if ($platformArgument) {
            $this->startCrawlerFor($platformArgument);
            return self::SUCCESS;
        }

        $this->startAllCrawlers();
        
        return self::SUCCESS;
    }

    private function startCrawlerFor(string|int $identifier): void
    {
        $platform = is_numeric($identifier)
            ? Platform::find($identifier)
            : Platform::where('name', $identifier)->first();

        if (!$platform) {
            $this->error("Platform not found: {$identifier}");
            Log::error("Crawler command failed: Platform not found: {$identifier}");
            return;
        }

        $this->info("Dispatching crawl job for {$platform->name}");
        CrawlJob::dispatch($platform);
    }

    private function startAllCrawlers(): void
    {
        $platforms = Platform::active()->get();

        if ($platforms->isEmpty()) {
            $this->warn('No active platforms found to crawl.');
            return;
        }

        $this->info("Dispatching crawl jobs for {$platforms->count()} platforms...");

        foreach ($platforms as $platform) {
            CrawlJob::dispatch($platform);
        }
    }
}
