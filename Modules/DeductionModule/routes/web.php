<?php

use Illuminate\Support\Facades\Route;
use Modules\DeductionModule\App\Http\Controllers\Admin\DeductionAdminController;

Route::prefix('admin/deduction')->name('admin.deductions.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',          [DeductionAdminController::class, 'index'])->name('index');
    Route::get('/create',    [DeductionAdminController::class, 'create'])->name('create');
    Route::post('/',         [DeductionAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [DeductionAdminController::class, 'edit'])->name('edit');
    Route::post('/{id}',     [DeductionAdminController::class, 'update'])->name('update');
    Route::delete('/{id}',   [DeductionAdminController::class, 'destroy'])->name('destroy');
});
