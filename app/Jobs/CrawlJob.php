<?php

namespace App\Jobs;

use App\Models\Platform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrawlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $platform;
    /**
     * Create a new job instance.
     */
    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $crawler = isset( $this->platform->name ) ? $this->platform->name : false;
        if(!$crawler) {
            throw new \Exception('Crawler not found');
        }

        $crawler = str_replace('20min', 'twentymin', $crawler);
        $crawler_class = 'App\Crawlers\\' . ucfirst($crawler);
        if(!class_exists($crawler_class)) {
            throw new \Exception('Crawler not found');
        }

        Log::info('Job for Crawler ' . $crawler . ' started');
        try {
            new $crawler_class();
        } catch (\Exception $e) {
            Log::error('Error in Crawler ' . $crawler . ': ' . $e->getMessage());
        }

        $this->platform->last_crawled = now();
        $this->platform->save();

        Log::info('Job for Crawler ' . $crawler . ' finished');
    }
}
