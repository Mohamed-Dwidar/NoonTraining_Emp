<?php

namespace Modules\PayrollModule\App\Http\Controllers\Admin;

use Modules\BonuseModule\App\Http\Models\Bonuse;
use Modules\DeductionModule\App\Http\Models\Deduction;
use Modules\LeaveModule\App\Http\Models\Leave;
use Modules\StudentModule\App\Http\Models\Student;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
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

    public function payslip($id) {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll) {
            return response()->json(['error' => 'not found'], 404);
        }
        $emp = $payroll->employee;
        return response()->json([
            'id'                   => $payroll->id,
            'month'                => $payroll->month,
            'employee_name'        => $emp->name ?? '—',
            'employee_job'         => $emp->job ?? '—',
            'branch_name'          => $emp->branch?->name ?? '—',
            'department_name'      => $emp->department?->name ?? '—',
            'branch_type'          => $emp->branch?->type ?? '',
            'monthly_working_days' => $payroll->monthly_working_days,
            'days_present'         => $payroll->days_present,
            'days_absent'          => $payroll->days_absent,
            'basic_salary'         => number_format($payroll->basic_salary, 2),
            'deductions'           => number_format($payroll->deductions, 2),
            'bonuses'              => number_format($payroll->bonuses, 2),
            'students_commission'  => number_format($payroll->students_commission, 2),
            'total_salary'         => number_format($payroll->total_salary, 2),
            'print_url'            => route('admin.payrolls.payslip.print', $id),
        ]);
    }

    public function payrollDetails($id) {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll) {
            return response()->json(['error' => 'not found'], 404);
        }

        $emp   = $payroll->employee;
        $empId = $emp->id;
        $month = $payroll->month;

        $bonuses    = Bonuse::where('employee_id', $empId)->where('month', $month)->get();
        $deductions = Deduction::where('employee_id', $empId)->where('month', $month)->get();
        $leaves     = Leave::where('employee_id', $empId)->where('month', $month)->get();
        $students   = Student::where('employee_id', $empId)->where('month', $month)->get();

        return response()->json([
            'employee_name'             => $emp->name ?? '—',
            'employee_job'              => $emp->job ?? '—',
            'branch_name'               => $emp->branch?->name ?? '—',
            'department_name'           => $emp->department?->name ?? '—',
            'month'                     => $month,
            'branch_type'               => $emp->branch?->type ?? '',
            'monthly_working_days'      => $payroll->monthly_working_days,
            'days_present'              => $payroll->days_present,
            'days_absent'               => $payroll->days_absent,
            'basic_salary'              => number_format($payroll->basic_salary, 2),
            'deductions_total'          => number_format($payroll->deductions, 2),
            'bonuses_total'             => number_format($payroll->bonuses, 2),
            'total_salary'              => number_format($payroll->total_salary, 2),
            'stu_commission_per'        => number_format((float) $emp->stu_commission, 2),
            'students_commission_total' => number_format($payroll->students_commission, 2),
            'pdf_url'                   => route('admin.payrolls.details.pdf', $id),
            'bonuses'    => $bonuses->map(fn($b) => [
                'reason' => $b->reason,
                'amount' => number_format($b->amount, 2),
            ]),
            'deductions' => $deductions->map(fn($d) => [
                'reason' => $d->reason,
                'amount' => number_format($d->amount, 2),
                'type'   => $d->type,
            ]),
            'leaves' => $leaves->map(fn($l) => [
                'type'  => $l->type,
                'days'  => $l->days,
                'start' => $l->start_date?->format('Y-m-d') ?? '—',
                'end'   => $l->end_date?->format('Y-m-d') ?? '—',
                'reason'=> $l->reason ?? '—',
            ]),
            'students' => $students->map(fn($s) => [
                'name'   => $s->name,
                'course' => $s->course_name,
            ]),
            'students_count' => $students->count(),
        ]);
    }

    public function payrollDetailsPdf($id) {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll) {
            abort(404);
        }

        $emp   = $payroll->employee;
        $empId = $emp->id;
        $month = $payroll->month;

        $bonuses    = Bonuse::where('employee_id', $empId)->where('month', $month)->get();
        $deductions = Deduction::where('employee_id', $empId)->where('month', $month)->get();
        $leaves     = Leave::where('employee_id', $empId)->where('month', $month)->get();
        $students   = Student::where('employee_id', $empId)->where('month', $month)->get();

        $fileName = 'details-' . ($emp->name ?? $id) . '-' . $month . '.pdf';

        $pdf = Pdf::loadView('payrollmodule::Admin.details_print', compact(
            'payroll', 'emp', 'bonuses', 'deductions', 'leaves', 'students'
        ));

        return $pdf->download($fileName);
    }

    public function payslipPrint($id) {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll) {
            abort(404);
        }
        $emp      = $payroll->employee;
        $fileName = 'payslip-' . ($emp->name ?? $id) . '-' . $payroll->month . '.pdf';

        $pdf = Pdf::loadView('payrollmodule::Admin.payslip_print', compact('payroll'));

        return $pdf->download($fileName);
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
