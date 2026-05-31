<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:multiple-session')->everyMinute();
Schedule::command('app:inactive-players')->everyMinute();
Schedule::command('app:helper-message')->hourly();
Schedule::command('app:daily-reset')->dailyAt('18:00')->timezone('Asia/Manila'); //6pm
