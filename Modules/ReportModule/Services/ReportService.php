<?php

namespace Modules\ReportModule\Services;

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
 * attributed to the employee who registered them (students.employee_id).
 * Period = students.month (شهر المحاسبة, the month the students were uploaded for) — same as
 * payroll commission. Not payment_date: employees often upload last month's payments.
 *
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

    /**
     * Period → [fromMonth, toMonth] ('YYYY-MM'), compared with students.month (شهر المحاسبة) —
     * the same month uploads and payroll use. custom = month_from..month_to (swapped if reversed).
     */
    public function resolveMonthRange(array $filters): array {
        $valid   = fn($m) => is_string($m) && preg_match('/^\d{4}-\d{2}$/', $m);
        $current = now()->format('Y-m');

        switch ($filters['period'] ?? 'current_month') {
            case 'previous_month':
                $from = $to = now()->subMonthNoOverflow()->format('Y-m');
                break;

            case 'month':
                $from = $to = $valid($filters['month'] ?? null) ? $filters['month'] : $current;
                break;

            case 'custom':
                $from = $valid($filters['month_from'] ?? null) ? $filters['month_from'] : $current;
                $to   = $valid($filters['month_to'] ?? null)   ? $filters['month_to']   : $current;
                if ($from > $to) {
                    [$from, $to] = [$to, $from];
                }
                break;

            case 'current_month':
            default:
                $from = $to = $current;
                break;
        }

        return [$from, $to];
    }

    /**
     * One row per employee with their students/admissions/revenue for the period.
     */
    public function employeePerformance(array $filters): Collection {
        [$from, $to] = $this->resolveMonthRange($filters);

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
            $month = now()->subMonthsNoOverflow($i)->format('Y-m');

            // One employee → that employee only; otherwise only the branch filter applies.
            $scope = $employeeId
                ? ['employee_id' => $employeeId]
                : ['branch_id' => $filters['branch_id'] ?? null];

            $agg = $this->studentRepository->performanceTotals($month, $month, $scope);

            $result->push((object) [
                'month'      => $month,
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
        [$from, $to] = $this->resolveMonthRange($filters);

        $students = $this->studentRepository->trainingRecordsByMonth($from, $to, ['employee_id' => $employeeId]);

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
