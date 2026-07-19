<?php

use App\Http\Controllers\Admin\AmountOverrideController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BulkOperationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientNoteController;
use App\Http\Controllers\CompanyLogoController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractOperationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuickSearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SealController;
use App\Http\Controllers\StoreItemController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('company-logo', CompanyLogoController::class)->name('company.logo');

    Route::prefix('admin')->name('admin.')->middleware('role:Administrator')->group(function () {
        Route::get('company', [CompanySettingController::class, 'edit'])->name('company.edit');
        Route::put('company', [CompanySettingController::class, 'update'])->name('company.update');

        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::get('overrides', [AmountOverrideController::class, 'index'])->name('overrides.index');
    });

    Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::get('clients/cities', [ClientController::class, 'cities'])->name('clients.cities');
    Route::post('clients/quick', [ClientController::class, 'quickStore'])->name('clients.quick-store');
    Route::post('clients/{client}/notes', [ClientNoteController::class, 'store'])->name('clients.notes.store');
    Route::delete('clients/{client}/notes/{note}', [ClientNoteController::class, 'destroy'])
        ->scopeBindings()->name('clients.notes.destroy');
    Route::resource('clients', ClientController::class)
        ->only(['index', 'create', 'store', 'show', 'update']);

    Route::resource('contracts', ContractController::class)
        ->only(['create', 'store', 'show']);
    Route::get('contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print');
    Route::post('contracts/{contract}/extensions', [ContractOperationController::class, 'extend'])->name('contracts.extend');
    Route::post('contracts/{contract}/redeem', [ContractOperationController::class, 'redeem'])->name('contracts.redeem');
    Route::post('contracts/{contract}/void', [ContractOperationController::class, 'void'])->name('contracts.void');
    Route::post('contracts/{contract}/forfeit', [ContractOperationController::class, 'forfeit'])->name('contracts.forfeit');
    Route::delete('contracts/{contract}/queue', [ContractOperationController::class, 'removeFromQueue'])->name('contracts.queue.remove');

    Route::get('seals', [SealController::class, 'index'])->name('seals.index');

    Route::get('store', [StoreItemController::class, 'index'])->name('store.index');
    Route::get('store/create', [StoreItemController::class, 'create'])->name('store.create');
    Route::post('store', [StoreItemController::class, 'store'])->name('store.store');

    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::get('sales/search-items', [SaleController::class, 'searchItems'])->name('sales.search-items');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');

    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    Route::get('search', QuickSearchController::class)->name('quick-search');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('expired', [ReportController::class, 'expired'])->name('expired');
        Route::get('queued', [ReportController::class, 'queued'])->name('queued');
        Route::get('active', [ReportController::class, 'active'])->name('active');
        Route::get('extensions', [ReportController::class, 'extensions'])->name('extensions');
        Route::get('sold', [ReportController::class, 'sold'])->name('sold');
        Route::get('financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('expenses', [ReportController::class, 'expenses'])->name('expenses');
        Route::get('pulled', [ReportController::class, 'pulled'])->name('pulled');
        Route::get('redeemed', [ReportController::class, 'redeemed'])->name('redeemed');
        Route::get('stats', [ReportController::class, 'stats'])->name('stats');
    });

    Route::prefix('operations')->name('operations.')->group(function () {
        Route::post('queue', [BulkOperationController::class, 'queue'])->name('queue');
        Route::post('pull', [BulkOperationController::class, 'pull'])->name('pull');
        Route::post('unqueue', [BulkOperationController::class, 'unqueue'])->name('unqueue');
    });
});
