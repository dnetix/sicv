<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/client/search', [\App\Http\Controllers\ClientController::class, 'search'])->name('api.client.search');
    Route::post('/client/store', [\App\Http\Controllers\ClientController::class, 'store'])->name('api.client.store');
});
