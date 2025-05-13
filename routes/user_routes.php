<?php
use App\Http\Controllers\UserProfileController;

Route::put('/user-profile', [UserProfileController::class, 'update'])->name('user-profile.update');