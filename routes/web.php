<?php

use App\Models\Platform;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('crawl/{crawler}', function ($crawler) {
    if( is_numeric($crawler) ) {
        $platform = Platform::find($crawler);
        if (!$platform) {
            dd('Invalid ID');
        }
        $crawler = $platform->name;
    }

    $crawler = 'App\Crawlers\\' . ucfirst($crawler);
    if(!class_exists($crawler)) {
        dd('Crawler not found');
    }
    new $crawler();
});
