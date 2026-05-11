<?php

use Illuminate\Support\Facades\Route;
use Modules\UserModule\App\Http\Controllers\Admin\UserAdminModuleController;
use Modules\UserModule\App\Http\Controllers\Auth\UserAuthController;

// Public user login routes
// Route::get('/', [UserAuthController::class, 'loginForm'])->name('user.loginForm');

Route::prefix('user')->group(function () {
    Route::get('/', [UserAuthController::class, 'loginForm']);
    Route::get('login', [UserAuthController::class, 'loginForm']);
    Route::post('login', [UserAuthController::class, 'login'])->name('user.loginpost');
});

// Admin routes for user management
Route::prefix('admin/users')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [UserAdminModuleController::class, 'listOfUsers'])->name('admin.users.list');
    Route::get('create', [UserAdminModuleController::class, 'create'])->name('admin.user.create');
    Route::post('store', [UserAdminModuleController::class, 'store'])->name('admin.user.store');
    Route::get('edit/{id}', [UserAdminModuleController::class, 'edit'])->name('admin.user.edit');
    Route::post('update', [UserAdminModuleController::class, 'update'])->name('admin.user.update');
    Route::get('delete/{id}', [UserAdminModuleController::class, 'destroy'])->name('admin.user.delete');
});

// Authenticated user routes
Route::prefix('user')->middleware(['auth:user'])->group(function () {
    Route::get('dashboard', [UserAuthController::class, 'dashboard'])->name('user.dashboard');
    Route::get('changePassword', [UserAuthController::class, 'changePassword'])->name('user.changePassword');
    Route::post('updatePassword', [UserAuthController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('logout', [UserAuthController::class, 'logout'])->name('user.logout');
});
