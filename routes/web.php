<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ViesController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    // Entity
    Route::get('/entities', [EntityController::class, 'index'])->name('entities.index');
    Route::get('/entities/create', [EntityController::class, 'create'])->name('entities.create');
    Route::post('/entities', [EntityController::class, 'store'])->name('entities.store');
    Route::get('/entities/{entity}', [EntityController::class, 'show'])->name('entities.show');
    Route::get('/entities/{entity}/edit', [EntityController::class, 'edit'])->name('entities.edit');
    Route::put('/entities/{entity}', [EntityController::class, 'update'])->name('entities.update');
    Route::delete('/entities/{entity}', [EntityController::class, 'destroy'])->name('entities.destroy');

    // Articles
    Route::resource('articles', ArticleController::class);

    // Vies API
    Route::post('/api/vies/check', [ViesController::class, 'check'])->name('api.vies.check');
});

require __DIR__.'/settings.php';
