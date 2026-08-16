<?php

use App\Http\Controllers\WhatsAppSseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

/*
 * Middleware `auth` bawaan Laravel me-redirect ke route bernama `login`.
 * Filament tidak mendaftarkan route dengan nama itu, jadi kita petakan
 * ke halaman login panel admin.
 */
Route::redirect('/login', '/admin/login')->name('login');

/*
 * Endpoint status + SSE WhatsApp untuk halaman Filament admin.
 * Hanya user yang sudah login (sesi web) yang bisa mengakses.
 */
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/whatsapp/state/{session}', [WhatsAppSseController::class, 'state'])
        ->where('session', '[A-Za-z0-9._-]+')
        ->name('whatsapp.state');

    Route::get('/whatsapp/sse/{session}', [WhatsAppSseController::class, 'stream'])
        ->where('session', '[A-Za-z0-9._-]+')
        ->name('whatsapp.sse');
});
