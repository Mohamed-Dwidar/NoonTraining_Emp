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
use Modules\ReportModule\Services\ReportService;

class ReportAdminController extends Controller {
    protected AttendanceService $attendanceService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;
    protected ReportService $reportService;

    public function __construct(
        AttendanceService $attendanceService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService,
        ReportService $reportService
    ) {
        $this->attendanceService = $attendanceService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
        $this->reportService     = $reportService;
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

    public function employeePerformance(Request $request) {
        $filters = $this->reportFilters($request);

        $rows     = $this->reportService->employeePerformance($filters);
        $kpis     = $this->reportService->kpiTotals($rows);
        $rankings = $this->reportService->rankings($rows);
        $trend    = $this->reportService->monthlyTrend($filters, $filters['employee_id'] ?: null);

        [$from, $to] = $this->reportService->resolveDateRange($filters);

        [$branches, $departments, $employees] = $this->trainingFilterOptions();

        return view('reportmodule::Admin.employee_performance', compact(
            'rows',
            'kpis',
            'rankings',
            'trend',
            'branches',
            'departments',
            'employees',
            'filters',
            'from',
            'to'
        ));
    }

    public function employeePerformanceData(Request $request) {
        $filters = $this->reportFilters($request);

        $rows     = $this->reportService->employeePerformance($filters);
        $kpis     = $this->reportService->kpiTotals($rows);
        $rankings = $this->reportService->rankings($rows);
        $trend    = $this->reportService->monthlyTrend($filters, $filters['employee_id'] ?: null);

        [$from, $to] = $this->reportService->resolveDateRange($filters);

        return response()->json([
            'from'     => $from,
            'to'       => $to,
            'kpis'     => $kpis,
            'rows'     => $rows,
            'rankings' => $rankings,
            'trend'    => $trend,
        ]);
    }

    public function employeePerformanceShow(Request $request, int $employeeId) {
        $employee = $this->employeeService->findOne($employeeId);
        if (!$employee || !$employee->branch || $employee->branch->type !== 'training') {
            abort(404);
        }

        $filters = $this->reportFilters($request);
        $data    = $this->reportService->employeeDrilldown($employeeId, $filters);

        return view('reportmodule::Admin.employee_drilldown', array_merge($data, [
            'employee' => $employee,
            'filters'  => $filters,
        ]));
    }

    /**
     * Reports only cover training-branch employees (students/revenue only apply there,
     * matching the same rule used for commissions elsewhere). Restrict the filter
     * dropdowns to that same set so the admin can't pick a branch/department/employee
     * that would only ever show empty results.
     */
    private function trainingFilterOptions(): array {
        $branches = $this->branchService->getAllBranches()->where('type', 'training')->values();
        $branchIds = $branches->pluck('id');

        $departments = $this->departmentService->getAllDepartments()
            ->whereIn('branch_id', $branchIds)
            ->values();

        $employees = $this->employeeService->getAllEmployees()
            ->whereIn('branch_id', $branchIds)
            ->values();

        return [$branches, $departments, $employees];
    }

    private function reportFilters(Request $request): array {
        return [
            'period'        => $request->input('period', 'current_month'),
            'month'         => $request->input('month'),
            'date_from'     => $request->input('date_from'),
            'date_to'       => $request->input('date_to'),
            'employee_id'   => $request->input('employee_id'),
            'branch_id'     => $request->input('branch_id'),
            'department_id' => $request->input('department_id'),
        ];
    }
}
