<?php

use Illuminate\Support\Facades\Route;
use Modules\LayoutModule\App\Http\Controllers\LayoutModuleController;


// Commented out to let StudentModule handle the root route
Route::get('/', function () {
    return view('layoutmodule::index');
})->name('home');
