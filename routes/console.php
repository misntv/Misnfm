<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

// Ubah dari Schedule::command('update:genre') menjadi Schedule::call
Schedule::call(function () {
    Artisan::call('update:genre');
})->everyMinute();