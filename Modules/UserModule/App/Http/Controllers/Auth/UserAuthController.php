<?php

namespace Modules\UserModule\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\AdminModule\App\Http\Models\Admin;
use Modules\EmployeeModule\App\Http\Models\Employee;
use Modules\UserModule\Services\UserService;

class UserAuthController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function dashboard() {
        if (Auth::guard('user')->check()) {

            $data = [];

            return view('usermodule::user.dashboard', $data);
        } else {
            return redirect('user/login');
        }
    }

    public function loginForm() {
        return view('usermodule::login');
    }

    public function login(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required',
                'email' => 'required',
            ],
            [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'password.required' =>  'كلمة المرور مطلوبه',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::validate($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials(['email' => $credentials['email']]);
            // dd($user->userable_type,Admin::class);
            if ($user->userable_type === Admin::class) {
                Auth::guard('admin')->login($user);
                return redirect()->route('admin.dashboard');
            } elseif ($user->userable_type === Employee::class) {
                if (in_array($user->userable->status, ['suspended', 'resigned', 'terminated', 'deleted'])) {        //suspended or resigned or terminated or deleted
                    return redirect()->back()->with('error', 'حسابك غير موجود او محظور')->withInput();
                }
                Auth::guard('employee')->login($user);
                return redirect()->route('employee.dashboard');
            }
        }

        return redirect()->back()->with('error', 'البريد الإلكتروني او كلمة المرور غير صحيحة')->withInput();
    }

    public function changePassword() {
        $user = auth()->guard('user')->user();
        return view('usermodule::user.change_password', compact('user'));
    }

    public function updatePassword(Request $request) {
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

        $user = auth()->guard('user')->user();
        $request['id'] = $user->id;

        if (Hash::check($request->old_password, $user->password)) {
            $this->userService->updatePassword($request);

            return redirect()->route(Auth::getDefaultDriver() . '.changePassword')
                ->with('success', 'تم تغيير كلمة المرور بنجاح');
        } else {
            return back()
                ->withErrors(['كلمة المرور القديمة غير صحيحة'])
                ->withInput();
        }
    }

    public function logout(Request $request) {

        Auth::guard('admin')->logout();
        Auth::guard('employee')->logout();

        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->to('/');
    }
}
