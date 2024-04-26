<?php

use App\Crawlers\Blick;
use App\Crawlers\Daydeal;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('crawl', function () {
    new Daydeal();
});

Route::get('crawl/{id}', function ($id) {
    if ($id == 1) {
        new Daydeal();
    } else if( $id == 2 ) {
        new Blick();
    }
});
