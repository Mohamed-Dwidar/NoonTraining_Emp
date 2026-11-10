<?php

namespace Modules\ReportModule\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\EmployeeModule\Repository\EmployeeRepository;
use Modules\StudentModule\Repository\StudentRepository;

/**
 * Employee performance reporting.
 *
 * Scope: only employees whose branch type is "training" are included —
 * student registrations only apply to training-branch staff, matching the
 * same rule already used for stu_commission / employee commissions elsewhere
 * in the app.
 *
 * Metrics are derived entirely from Student registrations (StudentModule),
 * attributed to the employee who registered them (students.employee_id):
 *
 * - Students   = distinct individuals (by national_id) an employee registered.
 *                A student with no national_id on file is counted as their own
 *                unique person (never silently dropped from the total).
 * - Admissions / Entries = total registration rows for the employee (one
 *   student enrolling in two courses = 2 admissions but 1 student). The
 *   system has no separate "usage" concept, so Usage is not reported —
 *   it would just duplicate Admissions.
 * - Revenue    = sum of paid_amount (money actually collected), consistent
 *                with how commission is already calculated in PayrollModule.
 *
 * All KPI totals are the sum of the employee-level rows, so dashboard totals
 * always match table totals by construction.
 */
class ReportService {
    protected StudentRepository $studentRepository;
    protected EmployeeRepository $employeeRepository;

    public function __construct(StudentRepository $studentRepository, EmployeeRepository $employeeRepository) {
        $this->studentRepository  = $studentRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function resolveDateRange(array $filters): array {
        $period = $filters['period'] ?? 'current_month';

        switch ($period) {
            case 'previous_month':
                $from = now()->subMonthNoOverflow()->startOfMonth();
                $to   = now()->subMonthNoOverflow()->endOfMonth();
                break;

            case 'month':
                $month = $filters['month'] ?? now()->format('Y-m');
                $from  = Carbon::parse($month . '-01')->startOfMonth();
                $to    = Carbon::parse($month . '-01')->endOfMonth();
                break;

            case 'custom':
                $from = !empty($filters['date_from']) ? Carbon::parse($filters['date_from']) : now()->startOfMonth();
                $to   = !empty($filters['date_to'])   ? Carbon::parse($filters['date_to'])   : now()->endOfMonth();
                break;

            case 'current_month':
            default:
                $from = now()->startOfMonth();
                $to   = now()->endOfMonth();
                break;
        }

        return [$from->format('Y-m-d'), $to->format('Y-m-d')];
    }

    /**
     * One row per employee with their students/admissions/revenue for the period.
     */
    public function employeePerformance(array $filters): Collection {
        [$from, $to] = $this->resolveDateRange($filters);

        $rows      = $this->studentRepository->performanceByEmployee($from, $to, $filters);
        $employees = $this->employeeRepository->findWithTrashedByIds($rows->pluck('employee_id'));

        return $rows->map(function ($row) use ($employees) {
            $employee = $employees->get($row->employee_id);

            return (object) [
                'employee_id'     => $row->employee_id,
                'employee_name'   => $employee->name ?? 'موظف غير معروف (#' . $row->employee_id . ')',
                'branch_name'     => $employee->branch->name ?? '—',
                'department_name' => $employee->department->name ?? '—',
                'students'        => (int) $row->students_count,
                'admissions'      => (int) $row->admissions_count,
                'revenue'         => (float) $row->revenue,
            ];
        })->sortByDesc('revenue')->values();
    }

    /**
     * KPI totals = sum of the employee-level rows, so they always match the table.
     */
    public function kpiTotals(Collection $rows): array {
        return [
            'students'   => (int) $rows->sum('students'),
            'admissions' => (int) $rows->sum('admissions'),
            'revenue'    => (float) $rows->sum('revenue'),
        ];
    }

    /**
     * Transparent top/bottom-5 rankings per existing KPI — no invented composite score.
     */
    public function rankings(Collection $rows): array {
        $byMetric = function (string $metric) use ($rows) {
            return [
                'top'    => $rows->sortByDesc($metric)->values()->take(5),
                'bottom' => $rows->sortBy($metric)->values()->take(5),
            ];
        };

        return [
            'students'   => $byMetric('students'),
            'admissions' => $byMetric('admissions'),
            'revenue'    => $byMetric('revenue'),
        ];
    }

    /**
     * Monthly trend for the last N months (or for one employee when $employeeId is given).
     */
    public function monthlyTrend(array $filters, ?int $employeeId = null, int $months = 6): Collection {
        $result = collect();

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthDate = now()->subMonthsNoOverflow($i);
            $from = $monthDate->copy()->startOfMonth()->format('Y-m-d');
            $to   = $monthDate->copy()->endOfMonth()->format('Y-m-d');

            // One employee → that employee only; otherwise only the branch filter applies.
            $scope = $employeeId
                ? ['employee_id' => $employeeId]
                : ['branch_id' => $filters['branch_id'] ?? null];

            $agg = $this->studentRepository->performanceTotals($from, $to, $scope);

            $result->push((object) [
                'month'      => $monthDate->format('Y-m'),
                'students'   => (int) $agg->students_count,
                'admissions' => (int) $agg->admissions_count,
                'revenue'    => (float) $agg->revenue,
            ]);
        }

        return $result;
    }

    /**
     * Full breakdown for a single employee: totals, monthly trend, and the underlying records.
     */
    public function employeeDrilldown(int $employeeId, array $filters): array {
        $filters['employee_id'] = $employeeId;
        [$from, $to] = $this->resolveDateRange($filters);

        $students = $this->studentRepository->trainingRecordsByPaymentDate($from, $to, ['employee_id' => $employeeId]);

        $uniqueStudents = $students->unique(fn($s) => $s->national_id ?: 'row-' . $s->id)->count();

        return [
            'from'       => $from,
            'to'         => $to,
            'students'   => $uniqueStudents,
            'admissions' => $students->count(),
            'revenue'    => (float) $students->sum('paid_amount'),
            'records'    => $students,
            'monthly'    => $this->monthlyTrend($filters, $employeeId, 6),
        ];
    }
}
