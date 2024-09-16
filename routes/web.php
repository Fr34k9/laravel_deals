<?php

use App\Models\Platform;
use App\Http\Controllers\ProfileController;
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

    Route::get('crawl/{crawler}', function ($crawler) {
        if( is_numeric($crawler) ) {
            $platform = Platform::find($crawler);
            if (!$platform) {
                dd('Invalid ID');
            }
            $crawler = $platform->name;
        }

        $crawler = str_replace('20min', 'twentymin', $crawler);
        $crawler = 'App\Crawlers\\' . ucfirst($crawler);
        if(!class_exists($crawler)) {
            dd('Crawler not found');
        }
        new $crawler();
    });
});

require __DIR__.'/auth.php';
