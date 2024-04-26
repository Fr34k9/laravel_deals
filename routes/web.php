<?php

use App\Crawlers\Blick;
use App\Crawlers\Daydeal;
use App\Crawlers\Qoqa;
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
    } else if( $id == 3 ) {
        new Qoqa();
    }
    else {
        dd('Invalid ID');
    }
});
