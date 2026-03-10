<?php

use App\Http\Controllers\ProfileController;
use App\Models\Platform;
use App\Services\CrawlerFactory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('crawl/{platform}', function ($identifier, CrawlerFactory $factory) {
        $platform = is_numeric($identifier)
            ? Platform::find($identifier)
            : Platform::where('name', $identifier)->first();

        if (! $platform) {
            abort(404, 'Platform not found');
        }

        try {
            $crawler = $factory->make($platform);
            $deals = $crawler->crawlDeals();
            $crawler->store($deals);

            $platform->update(['last_crawled' => now()]);

            return "Crawled {$platform->name} successfully. Found ".count($deals).' deals.';
        } catch (Throwable $e) {
            return "Error crawling {$platform->name}: ".$e->getMessage();
        }
    });
});

require __DIR__.'/auth.php';
