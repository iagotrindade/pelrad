<?php

use Illuminate\Foundation\Inspiring;
use App\Jobs\GenerateDailyCompliance;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new GenerateDailyCompliance)->dailyAt('23:59');
