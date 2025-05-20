<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Event management routes
Route::middleware('auth')->group(function () {
    Route::get('/events/dashboard', [EventController::class, 'dashboard'])->name('events.dashboard');
    Route::get('/events/search', [EventController::class, 'search'])->name('events.search');
    Route::patch('/events/{event}/status', [EventController::class, 'updateStatus'])->name('events.status.update');
    Route::resource('events', EventController::class);
});