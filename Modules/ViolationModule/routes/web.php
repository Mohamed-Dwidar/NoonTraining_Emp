<?php

use Illuminate\Support\Facades\Route;
use Modules\ViolationModule\App\Http\Controllers\Admin\ViolationAdminController;

Route::prefix('admin/violation')->name('admin.violations.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',          [ViolationAdminController::class, 'index'])->name('index');
    Route::get('/create',    [ViolationAdminController::class, 'create'])->name('create');
    Route::post('/',         [ViolationAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ViolationAdminController::class, 'edit'])->name('edit');
    Route::post('/{id}',     [ViolationAdminController::class, 'update'])->name('update');
    Route::delete('/{id}',   [ViolationAdminController::class, 'destroy'])->name('destroy');
});
