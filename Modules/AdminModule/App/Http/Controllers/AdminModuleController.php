<?php

namespace Modules\AdminModule\App\Http\Controllers;

use App\Http\Controllers\Controller;

class AdminModuleController extends Controller {

    public function __construct() {
    }

    public function dashboard() {
        $data = [];

        return view('adminmodule::admin.dashboard', $data);
    }
}
