<?php

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
    public function handle()
    {
        $platform = $this->argument('platform');
        if ($platform) {
            if (is_numeric($platform)) {
                $this->startCrawlerById($platform);
            } else {
                $this->startCrawlerByName($platform);
            }
        } else {
            $this->startAllCrawlers();
        }
    }

    private function startCrawlerById($id)
    {
        $platform = Platform::find($id);
        if (!$platform) {
            Log::error('Invalid ID');
            return;
        }

        CrawlJob::dispatch($platform);
    }

    private function startCrawlerByName($name)
    {
        $platform = Platform::where('name', $name)->first();
        if (!$platform) {
            Log::error('Invalid name');
            return;
        }
        CrawlJob::dispatch($platform);
    }

    private function startAllCrawlers()
    {
        $platforms = Platform::where('active', true)->get();
        foreach ($platforms as $platform) {
            CrawlJob::dispatch($platform);
        }
    }
}
