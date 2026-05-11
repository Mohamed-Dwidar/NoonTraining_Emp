<?php

use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {
    Route::get('/', 'Auth\AdminAuthController@index')->name('admin.login');
    Route::get('login', 'Auth\AdminAuthController@index');
    Route::post('login', 'Auth\AdminAuthController@login')->name('admin.loginpost');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::get('dashboard', 'AdminModuleController@dashboard')->name('admin.dashboard');
    Route::get('logout', 'Auth\AdminAuthController@logout')->name('admin.logout');
    Route::get('changePassword', 'Auth\AdminAuthController@changePassword')->name('admin.changePassword');
    Route::post('updatePassword', 'Auth\AdminAuthController@updatePassword')->name('admin.updatePassword');
});