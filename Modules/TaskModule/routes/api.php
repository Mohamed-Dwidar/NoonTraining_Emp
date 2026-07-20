<?php

use Illuminate\Support\Facades\Route;
use Modules\TaskModule\App\Http\Controllers\TaskModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('taskmodules', TaskModuleController::class)->names('taskmodule');
});
