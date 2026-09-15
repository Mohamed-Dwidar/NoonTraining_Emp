<?php

namespace Modules\EmployeeModule\App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Modules\CommissionModule\Services\CommissionService;

class EmployeeModuleController extends Controller
{
    protected CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function dashboard() {
        return view('employeemodule::employee.dashboard');
    }

    public function showMyInfo()
    {
        $employee = auth()->guard('employee')->user()->userable()->with('branch', 'department', 'commissions')->first();

        $commissions         = $this->commissionService->getAllCommissions();
        $employeeCommissions = $employee->commissions->keyBy('commission_id');

        return view('employeemodule::employee.my_info', compact('employee', 'commissions', 'employeeCommissions'));
    }

    public function changePassword()
    {
        $employee = auth()->guard('employee')->user();
        return view('employeemodule::employee.change_password', compact('employee'));
    }

    public function showWorkRegulations()
    {
        $employee = auth()->guard('employee')->user()->userable;
        abort_unless($employee->can_view_work_regulations, 403);
        $branch   = $employee->branch;
        return view('employeemodule::employee.work_regulations', compact('branch'));
    }

    public function downloadWorkRegulationsPdf()
    {
        $employee = auth()->guard('employee')->user()->userable;
        abort_unless($employee->can_view_work_regulations, 403);
        $branch   = $employee->branch;

        $fileName = 'لائحة العمل' . ($branch ? ' - ' . $branch->name : '') . '.pdf';

        $pdf = Pdf::loadView('employeemodule::employee.work_regulations_print', compact('branch'));

        return $pdf->download($fileName);
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
