<?php

use Illuminate\Support\Facades\Route;
use Modules\LayoutModule\App\Http\Controllers\LayoutModuleController;

Route::get('/', [LayoutModuleController::class, 'home'])->name('home');
