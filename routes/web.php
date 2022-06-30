<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/client/new', [ClientController::class, 'create'])->name('client.new');

    Route::get('/contract/view/{contract}', [ContractController::class, 'view'])->name('contract.view');
    Route::post('/contract/store', [ContractController::class, 'store'])->name('contract.store');
    Route::get('/contract/new/{client?}', [ContractController::class, 'create'])->name('contract.new');
    Route::get('/contract/print/{contract}', [ContractController::class, 'print'])->name('contract.print');
    Route::post('/contract/extension/{contract}', [ContractController::class, 'extension'])->name('contract.extension');
    Route::post('/contract/note/{contract}', [ContractController::class, 'note'])->name('contract.note');

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__ . '/auth.php';
