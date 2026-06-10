<?php

namespace Modules\AdminModule\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Modules\AdminModule\Services\AdminService;
use Modules\UserModule\App\Http\Models\User;

class AdminAuthController extends Controller
{
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('adminmodule::login');
        }
    }

    public function changePassword()
    {
        $admin = auth()->guard('admin')->user();
        return view('adminmodule::admin.change_password', compact('admin'));
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
        $user = auth()->guard('admin')->user();

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
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->to('admin');
    }
}
