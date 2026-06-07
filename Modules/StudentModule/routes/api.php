<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentModule\Http\Controllers\StudentModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('studentmodules', StudentModuleController::class)->names('studentmodule');
});
