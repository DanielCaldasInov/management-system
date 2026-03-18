<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuoteController;
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

    // Settings - Company
    Route::get('/settings/company', [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('/settings/company', [CompanyController::class, 'update'])->name('company.update');

    // Quotes
    Route::resource('quotes', QuoteController::class);
    Route::patch('/quotes/{quote}/status', [QuoteController::class, 'updateStatus'])->name('quotes.updateStatus');
    Route::get('/quotes/{quote}/pdf', [QuoteController::class, 'downloadPdf'])->name('quotes.pdf');
    Route::post('/quotes/{quote}/convert', [QuoteController::class, 'convertToOrder'])->name('quotes.convert');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{order}/lines', [OrderController::class, 'updateLines'])->name('orders.updateLines');

    // Vies API
    Route::post('/api/vies/check', [ViesController::class, 'check'])->name('api.vies.check');
});

require __DIR__.'/settings.php';
