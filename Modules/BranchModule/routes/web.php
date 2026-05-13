<?php

use Illuminate\Support\Facades\Route;
use Modules\BranchModule\App\Http\Controllers\Admin\BranchAdminController;

Route::prefix('admin/branch')->name('admin.branches.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [BranchAdminController::class, 'index'])->name('index');
    Route::get('create', [BranchAdminController::class, 'create'])->name('create');
    Route::post('/', [BranchAdminController::class, 'store'])->name('store');
    Route::get('{id}', [BranchAdminController::class, 'show'])->name('show');
    Route::get('edit/{id}', [BranchAdminController::class, 'edit'])->name('edit');
    Route::post('update', [BranchAdminController::class, 'update'])->name('update');
    Route::post('delete/{id}', [BranchAdminController::class, 'destroy'])->name('destroy');
});
