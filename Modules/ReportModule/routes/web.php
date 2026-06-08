<?php

use Illuminate\Support\Facades\Route;
use Modules\ReportModule\app\Http\Controllers\ReportModuleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::group(['prefix' => 'admin/reports', 'as' => 'admin.reports.' ,'middleware' => ['auth:admin']], function () {
Route::prefix('admin/reports')->name('admin.reports.')->middleware(['auth:admin'])->group(function () {
    Route::get('salary-report', 'Admin\ReportAdminController@ReportSalary')->name('salary-report');
});

Route::group(['prefix' => 'user/reports', 'middleware' => ['auth:user']], function () {
    Route::group(
        ['middleware' => ['permission:show_reports']],
        function () {
        }
    );
});
