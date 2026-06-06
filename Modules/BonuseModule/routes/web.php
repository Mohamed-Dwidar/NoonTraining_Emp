<?php

use Illuminate\Support\Facades\Route;
use Modules\BonuseModule\App\Http\Controllers\Admin\BonuseAdminController;

Route::prefix('admin/bonuse')->name('admin.bonuses.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',          [BonuseAdminController::class, 'index'])->name('index');
    Route::get('/create',    [BonuseAdminController::class, 'create'])->name('create');
    Route::post('/',         [BonuseAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BonuseAdminController::class, 'edit'])->name('edit');
    Route::post('/{id}',     [BonuseAdminController::class, 'update'])->name('update');
    Route::delete('/{id}',   [BonuseAdminController::class, 'destroy'])->name('destroy');
});
