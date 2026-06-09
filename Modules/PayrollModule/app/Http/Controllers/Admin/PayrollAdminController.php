<?php

namespace Modules\PayrollModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PayrollModule\Services\PayrollService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class PayrollAdminController extends Controller {
    protected PayrollService $payrollService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        PayrollService $payrollService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->payrollService = $payrollService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function index(Request $request) {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $month    = $request->input('month',         session('payroll_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('payroll_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['payroll_month' => $month, 'payroll_branch_id' => $branchId]);

        $payrolls = collect();

        if ($branchId) {
            $monthDate = $month;
            $employees = $this->employeeService->filter([
                'branch_id' => $branchId,
                'status' => ['active', 'on_leave']
            ])->get();
            $this->payrollService->ensureMonthlyRecords($employees, $monthDate); // ensure records exist for each employee

            $payrolls = $this->payrollService->filter([
                'month'         => $month,
                'branch_id'     => $branchId,
                'department_id' => $deptId,
            ])->paginate(15);
        }

        return view('payrollmodule::Admin.index', compact(
            'payrolls',
            'branches',
            'departments',
            'month',
            'branchId',
            'deptId'
        ));
    }
}
