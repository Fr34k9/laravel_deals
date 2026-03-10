<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Platform;
use App\Services\CrawlerFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class CrawlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Platform $platform
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CrawlerFactory $factory): void
    {
        Log::info("Job for Crawler {$this->platform->name} started");

        try {
            $crawler = $factory->make($this->platform);
            
            $deals = $crawler->crawlDeals();
            $crawler->store($deals);

            $this->platform->update([
                'last_crawled' => now(),
            ]);

            Log::info("Job for Crawler {$this->platform->name} finished");
        } catch (Throwable $e) {
            Log::error("Error in Crawler {$this->platform->name}: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            
            throw $e;
        }
    }
}
