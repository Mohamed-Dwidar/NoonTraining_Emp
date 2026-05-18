<?php

use Illuminate\Support\Facades\Route;
use Modules\DepartmentModule\Http\Controllers\DepartmentModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('departmentmodules', DepartmentModuleController::class)->names('departmentmodule');
});
