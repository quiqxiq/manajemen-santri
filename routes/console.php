<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pengingat harian otomatis WhatsApp:
Schedule::command('whatsapp:pengingat-perizinan-kembali')->dailyAt('08:00');
Schedule::command('whatsapp:pengingat-jatuh-tempo')->dailyAt('07:30');
