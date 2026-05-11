<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the appointment reminders to run every 10 minutes for testing
Schedule::command('app:send-appointment-reminders')->everyTenMinutes();

// Schedule the daily appointment reports to run every 10 minutes for testing
Schedule::command('app:send-daily-reports')->everyTenMinutes();
