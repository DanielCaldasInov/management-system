<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('Settings', '/Settings/profile');

    Route::get('Settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('Settings/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('Settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('Settings/password', [PasswordController::class, 'edit'])->name('user-password.edit');

    Route::put('Settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::inertia('Settings/appearance', 'Settings/Appearance')->name('appearance.edit');

    Route::get('Settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');
});
