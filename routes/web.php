<?php

use App\Crawlers\Daydeal;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('crawl', function () {
    new Daydeal();
});
