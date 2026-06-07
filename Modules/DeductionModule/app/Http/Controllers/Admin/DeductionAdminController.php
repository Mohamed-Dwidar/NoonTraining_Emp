<?php

namespace Modules\DeductionModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\DeductionModule\Services\DeductionService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class DeductionAdminController extends Controller
{
    protected DeductionService $deductionService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        DeductionService $deductionService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->deductionService  = $deductionService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function index(Request $request)
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $month    = $request->input('month',         session('deduction_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('deduction_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['deduction_month' => $month, 'deduction_branch_id' => $branchId]);

        $deductions = $this->deductionService->getDeductions(
            $month,
            $branchId ? (int) $branchId : null,
            $deptId   ? (int) $deptId   : null
        );

        return view('deductionmodules::Admin.index', compact(
            'deductions', 'branches', 'departments', 'month', 'branchId', 'deptId'
        ));
    }

    public function create(Request $request)
    {
        $month       = $request->input('month', session('deduction_month', now()->format('Y-m')));
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('deductionmodules::Admin.create', compact(
            'month', 'branches', 'departments', 'employees'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'month'       => 'required|string|size:7',
                'amount'      => 'required|numeric|min:0',
                'reason'      => 'nullable|string',
            ],
            [
                'employee_id.required' => 'الموظف مطلوب',
                'employee_id.exists'   => 'الموظف غير موجود',
                'month.required'       => 'الشهر مطلوب',
                'month.size'           => 'صيغة الشهر غير صحيحة',
                'amount.required'      => 'مبلغ الخصم مطلوب',
                'amount.numeric'       => 'مبلغ الخصم يجب أن يكون رقماً',
                'amount.min'           => 'مبلغ الخصم يجب أن يكون أكبر من صفر',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->deductionService->createDeduction(
            $request->only('employee_id', 'month', 'amount', 'reason')
        );

        return redirect()->route('admin.deductions.index', ['month' => $request->month])
            ->with('success', 'تم إضافة الخصم بنجاح');
    }

    public function edit(int $id)
    {
        $deduction   = $this->deductionService->findDeduction($id);
        $month       = $deduction->month;
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('deductionmodules::Admin.edit', compact(
            'deduction', 'month', 'branches', 'departments', 'employees'
        ));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'month'       => 'required|string|size:7',
                'amount'      => 'required|numeric|min:0',
                'reason'      => 'nullable|string',
            ],
            [
                'employee_id.required' => 'الموظف مطلوب',
                'employee_id.exists'   => 'الموظف غير موجود',
                'month.required'       => 'الشهر مطلوب',
                'month.size'           => 'صيغة الشهر غير صحيحة',
                'amount.required'      => 'مبلغ الخصم مطلوب',
                'amount.numeric'       => 'مبلغ الخصم يجب أن يكون رقماً',
                'amount.min'           => 'مبلغ الخصم يجب أن يكون أكبر من صفر',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->deductionService->updateDeduction(
            $id,
            $request->only('employee_id', 'month', 'amount', 'reason')
        );

        return redirect()->route('admin.deductions.index', ['month' => $request->month])
            ->with('success', 'تم تحديث الخصم بنجاح');
    }

    public function destroy(int $id)
    {
        $deduction = $this->deductionService->findDeduction($id);
        $month     = $deduction->month;
        $this->deductionService->deleteDeduction($id);

        return redirect()->route('admin.deductions.index', ['month' => $month])
            ->with('success', 'تم حذف الخصم بنجاح');
    }
}
