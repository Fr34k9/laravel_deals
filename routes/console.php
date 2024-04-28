<?php

use App\Jobs\CrawlJob;
use App\Models\Platform;
use Illuminate\Support\Facades\Schedule;

$platforms = Platform::where('active', true)->get();
foreach ($platforms as $platform) {
    Schedule::job(new CrawlJob($platform))->everyFiveMinutes();
}
