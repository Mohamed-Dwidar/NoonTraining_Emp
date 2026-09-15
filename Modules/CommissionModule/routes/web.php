<?php

use Illuminate\Support\Facades\Route;
use Modules\CommissionModule\App\Http\Controllers\Admin\CommissionAdminController;

Route::prefix('admin/commission')->name('admin.commissions.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [CommissionAdminController::class, 'index'])->name('index');
    Route::get('create', [CommissionAdminController::class, 'create'])->name('create');
    Route::post('/', [CommissionAdminController::class, 'store'])->name('store');
    Route::get('edit/{id}', [CommissionAdminController::class, 'edit'])->name('edit');
    Route::post('update', [CommissionAdminController::class, 'update'])->name('update');
    Route::post('delete/{id}', [CommissionAdminController::class, 'destroy'])->name('destroy');
});
