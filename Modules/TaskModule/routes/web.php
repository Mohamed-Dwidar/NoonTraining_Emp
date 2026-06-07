<?php

use Illuminate\Support\Facades\Route;
use Modules\TaskModule\App\Http\Controllers\Admin\TaskAdminController;

Route::prefix('admin/task')->name('admin.tasks.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',                  [TaskAdminController::class, 'index'])->name('index');
    Route::get('/create',            [TaskAdminController::class, 'create'])->name('create');
    Route::post('/',                 [TaskAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit',         [TaskAdminController::class, 'edit'])->name('edit');
    Route::post('/{id}',             [TaskAdminController::class, 'update'])->name('update');
    Route::patch('/{id}/status',     [TaskAdminController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}',           [TaskAdminController::class, 'destroy'])->name('destroy');
});
