<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('app:send-verification-reminders')
    ->weeklyOn(5, '16:00') // vendredi 16h UTC = 17h Cameroun
    ->timezone('Africa/Douala')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('Rappels vérification email envoyés avec succès.');
    });