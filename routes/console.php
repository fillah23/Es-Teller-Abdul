<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyFiveMinutes();
// Jadwalkan backup database setiap hari jam 2 pagi
Schedule::command('backup:database')->everyFiveMinutes();