<?php

namespace Modules\AdminModule\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Modules\AdminModule\Services\AdminService;

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

    public function login(Request $request)
    {
        $rememberme = $request->has('rememberme') ? 1 : 0;

        if (auth('admin')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ],
            $rememberme
        )) {
            return redirect()->intended('admin');
        }

        return redirect()->back()->withErrors(['error' => 'البريد الأليكتروني او كلمة المرور ']);
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

        $admin = auth()->guard('admin')->user();
        $request['id'] = $admin->id;

        if (Hash::check($request->old_password, $admin->password)) {
            $this->adminService->updatePassword($request);

            return redirect()->route(Auth::getDefaultDriver() . '.changePassword')
                ->with('success', 'تم تغيير كلمة المرور بنجاح');
        } else {
            return back()
                ->withErrors(['كلمة المرو القديمة غير صحيحة'])
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
