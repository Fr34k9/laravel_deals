<?php

use App\Crawlers\Blick;
use App\Crawlers\Daydeal;
use App\Crawlers\Digitec;
use App\Crawlers\Galaxus;
use App\Crawlers\Qoqa;
use App\Models\Platform;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('crawl', function () {
    new Daydeal();
    new Blick();
    new Qoqa();
    new Digitec();
    new Galaxus();
});

Route::get('crawl/{crawler}', function ($crawler) {
    if( is_numeric($crawler) ) {
        $platform = Platform::find($crawler);
        if (!$platform) {
            dd('Invalid ID');
        }
        $crawler = $platform->name;
    }
    $crawler = 'App\Crawlers\\' . ucfirst($crawler);
    new $crawler();
});

Route::get('crawl/by-id/{id}', function ($id) {
    $platform = Platform::find($id);
    if (!$platform) {
        dd('Invalid ID');
    }

    $crawler = 'App\Crawlers\\' . ucfirst($platform->name);
    if(!class_exists($crawler)) {
        dd('Crawler not found');
    }
    new $crawler();
});
