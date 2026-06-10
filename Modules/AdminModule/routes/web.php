<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminModule\App\Http\Controllers\AdminModuleController;
use Modules\AdminModule\App\Http\Controllers\Auth\AdminAuthController;
use Modules\UserModule\App\Http\Controllers\Auth\UserAuthController;

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'index'])->name('admin.login');
    Route::post('login', [UserAuthController::class, 'login'])->name('admin.loginpost');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [AdminModuleController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('dashboard', [AdminModuleController::class, 'dashboard'])->name('admin.admin_dashboard');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('changePassword', [AdminAuthController::class, 'changePassword'])->name('admin.changePassword');
    Route::post('updatePassword', [AdminAuthController::class, 'updatePassword'])->name('admin.updatePassword');
});
