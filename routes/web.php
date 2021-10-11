<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::post('/contract/store', [\App\Http\Controllers\ContractController::class, 'store'])
        ->name('contract.store');
    Route::get('/contract/new/{client?}', [\App\Http\Controllers\ContractController::class, 'create'])
        ->name('contract.new');

    Route::get('/dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__ . '/auth.php';
