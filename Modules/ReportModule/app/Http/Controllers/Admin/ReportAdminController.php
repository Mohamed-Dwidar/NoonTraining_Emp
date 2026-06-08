<?php

namespace Modules\ReportModule\app\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\AttendanceModule\Services\AttendanceService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class ReportAdminController extends Controller {
    protected AttendanceService $attendanceService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        AttendanceService $attendanceService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->attendanceService = $attendanceService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function ReportSalary(Request $request) {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $month    = $request->input('month',         session('attendance_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('attendance_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['attendance_month' => $month, 'attendance_branch_id' => $branchId]);

        $attendances = collect();

        if ($branchId) {
            $monthDate = $month;

            $employees = $this->employeeService->findWhere(['branch_id' => $branchId]);
            $this->attendanceService->ensureMonthlyRecords($employees, $monthDate);

            $attendances = $this->attendanceService->filter([
                'month'         => $month,
                'branch_id'     => $branchId,
                'department_id' => $deptId,
            ])->paginate(50);
        }

        return view('reportmodule::Admin.salary_report', compact(
            'attendances',
            'branches',
            'departments',
            'month',
            'branchId',
            'deptId'
        ));
    }
}
