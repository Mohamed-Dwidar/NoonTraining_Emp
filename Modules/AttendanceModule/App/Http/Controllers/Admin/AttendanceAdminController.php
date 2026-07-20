<?php

namespace Modules\AttendanceModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AttendanceModule\Services\AttendanceService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class AttendanceAdminController extends Controller
{
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

    public function index(Request $request)
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $month    = $request->input('month',         session('attendance_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('attendance_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['attendance_month' => $month, 'attendance_branch_id' => $branchId]);

        $attendances = collect();

        if ($branchId) {
            $monthDate = $month . '-01';

            $employees = $this->employeeService->findWhere(['branch_id' => $branchId]);
            $this->attendanceService->ensureMonthlyRecords($employees, $monthDate);

            $attendances = $this->attendanceService->getMonthlyAttendances(
                $monthDate,
                (int) $branchId,
                $deptId ? (int) $deptId : null
            );
        }

        return view('attendancemodule::Admin.index', compact(
            'attendances', 'branches', 'departments', 'month', 'branchId', 'deptId'
        ));
    }

    public function store(Request $request)
    {
        $month    = $request->input('month');
        $branchId = $request->input('branch_id');

        $this->attendanceService->saveMonthlyAttendances(
            $request->input('attendance', []),
            $month . '-01'
        );

        return redirect()->route('admin.attendances.index', [
            'month'     => $month,
            'branch_id' => $branchId,
        ])->with('success', 'تم حفظ بيانات الحضور بنجاح');
    }
}
