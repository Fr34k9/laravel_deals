<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('crawl:start')->everyTwoMinutes();
