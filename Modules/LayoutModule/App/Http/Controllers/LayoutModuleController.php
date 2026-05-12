<?php

namespace Modules\LayoutModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LayoutModuleController extends Controller {
    public function home() {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }

        return redirect()->route('login');
    }
}
