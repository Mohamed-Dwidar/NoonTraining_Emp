<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminModule\App\Http\Controllers\AdminModuleController;
use Modules\UserModule\App\Http\Controllers\Auth\UserAuthController;

Route::prefix('admin')->group(function () {
    Route::get('/', 'AdminModuleController@dsashboard')->name('admin.dashboard');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [AdminModuleController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('dashboard', [AdminModuleController::class, 'dashboard'])->name('admin.admin_dashboard');
    Route::get('logout', [UserAuthController::class, 'logout'])->name('admin.logout');
    Route::get('changePassword', [UserAuthController::class, 'updatePassword'])->name('admin.changePassword');
    Route::get('updatePassword', [UserAuthController::class, 'updatePassword'])->name('admin.updatePassword');
});
