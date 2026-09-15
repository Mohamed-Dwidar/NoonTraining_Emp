<?php

namespace Modules\PayrollModule\App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BonuseModule\App\Http\Models\Bonuse;
use Modules\DeductionModule\App\Http\Models\Deduction;
use Modules\LeaveModule\App\Http\Models\Leave;
use Modules\StudentModule\App\Http\Models\Student;
use Modules\PayrollModule\Services\PayrollService;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PayrollModuleController extends Controller
{
    protected PayrollService $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    private function currentEmployeeId(): int
    {
        return (int) auth()->guard('employee')->user()->userable_id;
    }

    /**
     * List the logged-in employee's own payroll records, newest month first.
     */
    public function myPayrolls()
    {
        $payrolls = $this->payrollService->filter(['employee_id' => $this->currentEmployeeId()])
            ->orderByDesc('month')
            ->paginate(15);

        return view('payrollmodule::Employee.index', compact('payrolls'));
    }

    public function myPayslip($id)
    {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll || (int) $payroll->employee_id !== $this->currentEmployeeId()) {
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
            'print_url'            => route('employee.payrolls.payslip.print', $id),
        ]);
    }

    public function myPayslipPrint($id)
    {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll || (int) $payroll->employee_id !== $this->currentEmployeeId()) {
            abort(404);
        }

        $emp      = $payroll->employee;
        $fileName = 'payslip-' . ($emp->name ?? $id) . '-' . $payroll->month . '.pdf';

        $pdf = Pdf::loadView('payrollmodule::Admin.payslip_print', compact('payroll'));

        return $pdf->download($fileName);
    }

    public function myPayrollDetails($id)
    {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll || (int) $payroll->employee_id !== $this->currentEmployeeId()) {
            return response()->json(['error' => 'not found'], 404);
        }

        $emp   = $payroll->employee;
        $empId = $emp->id;
        $month = $payroll->month;

        $bonuses    = Bonuse::where('employee_id', $empId)->where('month', $month)->get();
        $deductions = Deduction::where('employee_id', $empId)->where('month', $month)->get();
        $leaves     = Leave::where('employee_id', $empId)->where('month', $month)->get();
        $students   = Student::where('employee_id', $empId)->paidInMonth($month)->get();

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
            'pdf_url'                   => route('employee.payrolls.details.pdf', $id),
            'bonuses'    => $bonuses->map(fn($b) => [
                'reason' => $b->reason,
                'amount' => number_format($b->amount, 2),
            ]),
            'deductions' => $deductions->map(fn($d) => [
                'reason'     => $d->reason,
                'amount'     => number_format($d->amount, 2),
                'type'       => $d->type,
                'created_at' => $d->created_at?->format('Y-m-d') ?? '—',
            ]),
            'leaves' => $leaves->map(fn($l) => [
                'type'  => $l->type,
                'days'  => $l->days,
                'start' => $l->start_date?->format('Y-m-d') ?? '—',
                'end'   => $l->end_date?->format('Y-m-d') ?? '—',
                'reason' => $l->reason ?? '—',
            ]),
            'students' => $students->map(fn($s) => [
                'name'   => $s->name,
                'course' => $s->course_name,
            ]),
            'students_count' => $students->count(),
        ]);
    }

    public function myPayrollDetailsPdf($id)
    {
        $payroll = $this->payrollService->findOne($id);
        if (!$payroll || (int) $payroll->employee_id !== $this->currentEmployeeId()) {
            abort(404);
        }

        $emp   = $payroll->employee;
        $empId = $emp->id;
        $month = $payroll->month;

        $bonuses    = Bonuse::where('employee_id', $empId)->where('month', $month)->get();
        $deductions = Deduction::where('employee_id', $empId)->where('month', $month)->get();
        $leaves     = Leave::where('employee_id', $empId)->where('month', $month)->get();
        $students   = Student::where('employee_id', $empId)->paidInMonth($month)->get();

        $fileName = 'details-' . ($emp->name ?? $id) . '-' . $month . '.pdf';

        $pdf = Pdf::loadView('payrollmodule::Admin.details_print', compact(
            'payroll',
            'emp',
            'bonuses',
            'deductions',
            'leaves',
            'students'
        ));

        return $pdf->download($fileName);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('payrollmodule::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('payrollmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payrollmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payrollmodule::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
