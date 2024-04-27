<?php

use App\Crawlers\Blick;
use App\Crawlers\Daydeal;
use App\Crawlers\Digitec;
use App\Crawlers\Galaxus;
use App\Crawlers\Qoqa;
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
    $crawler = 'App\Crawlers\\' . ucfirst($crawler);
    new $crawler();
});

Route::get('crawl/by-id/{id}', function ($id) {
    if ($id == 1) {
        new Daydeal();
    } else if( $id == 2 ) {
        new Blick();
    } else if( $id == 3 ) {
        new Qoqa();
    } else if( $id == 4 ) {
        new Digitec();
    } else if( $id == 5 ) {
        new Galaxus();
    }
    else {
        dd('Invalid ID');
    }
});
