<?php

namespace Modules\LeaveModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\LeaveModule\App\Http\Models\Leave;
use Modules\LeaveModule\Services\LeaveService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class LeaveAdminController extends Controller
{
    protected LeaveService $leaveService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        LeaveService $leaveService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->leaveService      = $leaveService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function index(Request $request)
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $month    = $request->input('month',         session('leave_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('leave_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['leave_month' => $month, 'leave_branch_id' => $branchId]);

        $leaves = $this->leaveService->getLeaves(
            $month,
            $branchId ? (int) $branchId : null,
            $deptId   ? (int) $deptId   : null
        );

        return view('leavemodule::Admin.index', compact(
            'leaves', 'branches', 'departments', 'month', 'branchId', 'deptId'
        ));
    }

    public function create(Request $request)
    {
        $month       = $request->input('month', session('leave_month', now()->format('Y-m')));
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();
        $types       = Leave::TYPES;

        return view('leavemodule::Admin.create', compact(
            'month', 'branches', 'departments', 'employees', 'types'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'type'        => 'required|string',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date|after_or_equal:start_date',
                'reason'      => 'nullable|string',
            ],
            [
                'employee_id.required' => 'الموظف مطلوب',
                'employee_id.exists'   => 'الموظف غير موجود',
                'type.required'        => 'نوع الإجازة مطلوب',
                'start_date.required'  => 'تاريخ البداية مطلوب',
                'start_date.date'      => 'تاريخ البداية غير صحيح',
                'end_date.required'    => 'تاريخ النهاية مطلوب',
                'end_date.date'        => 'تاريخ النهاية غير صحيح',
                'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->leaveService->createLeave(
            $request->only('employee_id', 'type', 'start_date', 'end_date', 'reason')
        );

        return redirect()->route('admin.leaves.index', [
            'month' => substr($request->start_date, 0, 7),
        ])->with('success', 'تم إضافة الإجازة بنجاح');
    }

    public function edit(int $id)
    {
        $leave       = $this->leaveService->findLeave($id);
        $month       = $leave->month;
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();
        $types       = Leave::TYPES;

        return view('leavemodule::Admin.edit', compact(
            'leave', 'month', 'branches', 'departments', 'employees', 'types'
        ));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'type'        => 'required|string',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date|after_or_equal:start_date',
                'reason'      => 'nullable|string',
            ],
            [
                'employee_id.required' => 'الموظف مطلوب',
                'employee_id.exists'   => 'الموظف غير موجود',
                'type.required'        => 'نوع الإجازة مطلوب',
                'start_date.required'  => 'تاريخ البداية مطلوب',
                'start_date.date'      => 'تاريخ البداية غير صحيح',
                'end_date.required'    => 'تاريخ النهاية مطلوب',
                'end_date.date'        => 'تاريخ النهاية غير صحيح',
                'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->leaveService->updateLeave(
            $id,
            $request->only('employee_id', 'type', 'start_date', 'end_date', 'reason')
        );

        return redirect()->route('admin.leaves.index', [
            'month' => substr($request->start_date, 0, 7),
        ])->with('success', 'تم تحديث الإجازة بنجاح');
    }

    public function destroy(int $id)
    {
        $leave = $this->leaveService->findLeave($id);
        $month = $leave->month;
        $this->leaveService->deleteLeave($id);

        return redirect()->route('admin.leaves.index', ['month' => $month])
            ->with('success', 'تم حذف الإجازة بنجاح');
    }
}
