<?php
use App\Http\Controllers\UserProfileController;

Route::middleware('auth')->group(function () {
    Route::post('/user-profile', [UserProfileController::class, 'update'])->name('user-profile.update');
});