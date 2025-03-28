<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('backup:run');
// Schedule::command('backup:run')->daily()->at('00:00');//12:00 AM
// Schedule::call('backup:clean')->daily()->at('01:00');//1:00 AM

