<?php

namespace Modules\UserModule\App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\PermissionModule\Services\PermissionService;
use Modules\UserModule\Services\UserService;

class UserAdminModuleController extends Controller
{
    private $userService;
    private $permissionService;

    public function __construct(UserService $userService, PermissionService $permissionService)
    {
        $this->userService = $userService;
        $this->permissionService = $permissionService;
    }

    public function loginForm()
    {
        if (Auth::guard('user')->check()) {
            return redirect()->route('user.dashboard');
        } else {
            return view('usermodule::admin.login');
        }
    }

    public function index()
    {
        if (Auth::guard('user')->check()) {
            return view('usermodule::admin.dashboard');
        } else {
            return redirect('user/login');
        }
    }

    public function login(Request $request)
    {
        if (auth('user')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ],
        )) {
            return redirect()->route('user.dashboard');
        }

        return redirect()->back()->withErrors(['error' => 'error in password or email']);
    }

    public function listOfUsers()
    {
        $users = $this->userService->findAll();
        return view('usermodule::admin.index', compact('users'));
    }

    public function create()
    {
        return view('usermodule::admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:4',
            ],
            [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.unique' => 'البريد الإلكتروني موجود بالفعل',
                'email.email' => 'البريد الإلكتروني غير صالح',
                'password.min' => 'كلمة المرور يجب ان لا تقل عن 4 ارقام',
                'password.required' => 'كلمة المرور مطلوبه',
                'name.required' => 'الاسم مطلوب',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->userService->create($request);

        return redirect()->route(Auth::getDefaultDriver() . '.users.list')
            ->with('success', __('messages.successfully_saved'));
    }

    public function edit($id)
    {
        $user = $this->userService->findOne($id);
        $allPermissions = $this->permissionService->findAll();
        $userPermissions = old('permissions', $user->permissions->pluck('name')->toArray());

        return view('usermodule::admin.edit', compact('user', 'allPermissions','userPermissions'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
            ],
            [
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.unique' => 'البريد الإلكتروني موجود بالفعل',
                'email.email' => 'البريد الإلكتروني غير صالح',
                'name.required' => 'الاسم مطلوب',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = $this->userService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.users.list')
            ->with('success', 'Updated Successfully.');
    }

    public function destroy($id)
    {
        $this->userService->deleteOne($id);
        return redirect()->route(Auth::getDefaultDriver() . '.users.list')->with('success', 'deleted Successfully.');
    }

    public function logout(Request $request)
    {
        Auth::guard('user')->logout();
        return redirect()->to('user');
    }
}
