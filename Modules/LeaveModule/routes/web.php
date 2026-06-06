<?php

use Illuminate\Support\Facades\Route;
use Modules\LeaveModule\App\Http\Controllers\Admin\LeaveAdminController;

Route::prefix('admin/leave')->name('admin.leaves.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',          [LeaveAdminController::class, 'index'])->name('index');
    Route::get('/create',    [LeaveAdminController::class, 'create'])->name('create');
    Route::post('/',         [LeaveAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LeaveAdminController::class, 'edit'])->name('edit');
    Route::post('/{id}',     [LeaveAdminController::class, 'update'])->name('update');
    Route::delete('/{id}',   [LeaveAdminController::class, 'destroy'])->name('destroy');
});
