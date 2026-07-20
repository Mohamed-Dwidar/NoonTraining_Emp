<?php

namespace Modules\EmployeeModule\App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeModuleController extends Controller
{

    public function dashboard() {
        return view('employeemodule::employee.dashboard');
    }

    public function changePassword()
    {
        $employee = auth()->guard('employee')->user();
        return view('employeemodule::employee.change_password', compact('employee'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:4',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        /** @var User $user */
        $user = auth()->guard('employee')->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => bcrypt($request->password)]);

            return redirect()->route(Auth::getDefaultDriver() . '.changePassword')
                ->with('success', 'تم تغيير كلمة المرور بنجاح');
        } else {
            return back()
                ->withErrors(['كلمة المرور القديمة غير صحيحة'])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->to('employee');
    }
}
