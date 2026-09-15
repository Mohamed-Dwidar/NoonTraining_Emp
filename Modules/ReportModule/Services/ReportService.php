<?php

namespace Modules\ReportModule\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\EmployeeModule\App\Http\Models\Employee;
use Modules\StudentModule\App\Http\Models\Student;

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
    private const DISTINCT_STUDENT_EXPR = "COUNT(DISTINCT COALESCE(NULLIF(national_id, ''), CONCAT('row-', id)))";

    /** payment_date is a free-text string column; guard against malformed values (e.g. a raw Excel serial) skewing date-range comparisons. */
    private const VALID_DATE_EXPR = "payment_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'";

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

    private function baseQuery(array $filters) {
        [$from, $to] = $this->resolveDateRange($filters);

        return Student::query()
            ->whereRaw(self::VALID_DATE_EXPR)
            ->whereBetween('payment_date', [$from, $to])
            ->whereHas('employee.branch', fn($q) => $q->where('type', 'training'))
            ->when(!empty($filters['employee_id']), fn($q) => $q->where('employee_id', $filters['employee_id']))
            ->when(!empty($filters['branch_id']), fn($q) => $q->whereHas('employee', fn($q2) => $q2->where('branch_id', $filters['branch_id'])))
            ->when(!empty($filters['department_id']), fn($q) => $q->whereHas('employee', fn($q2) => $q2->where('department_id', $filters['department_id'])));
    }

    /**
     * One row per employee with their students/admissions/revenue for the period.
     */
    public function employeePerformance(array $filters): Collection {
        $rows = $this->baseQuery($filters)
            ->selectRaw('employee_id,'
                . self::DISTINCT_STUDENT_EXPR . ' as students_count,'
                . 'COUNT(*) as admissions_count,'
                . 'COALESCE(SUM(paid_amount), 0) as revenue')
            ->groupBy('employee_id')
            ->get();

        $employees = Employee::withTrashed()->with('branch', 'department')
            ->whereIn('id', $rows->pluck('employee_id'))
            ->get()
            ->keyBy('id');

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

            $query = Student::whereRaw(self::VALID_DATE_EXPR)
                ->whereBetween('payment_date', [$from, $to])
                ->whereHas('employee.branch', fn($q) => $q->where('type', 'training'));

            if ($employeeId) {
                $query->where('employee_id', $employeeId);
            } elseif (!empty($filters['branch_id'])) {
                $query->whereHas('employee', fn($q) => $q->where('branch_id', $filters['branch_id']));
            }

            $agg = $query->selectRaw(self::DISTINCT_STUDENT_EXPR . ' as students_count,'
                . 'COUNT(*) as admissions_count,'
                . 'COALESCE(SUM(paid_amount), 0) as revenue')
                ->first();

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

        $students = Student::where('employee_id', $employeeId)
            ->whereRaw(self::VALID_DATE_EXPR)
            ->whereBetween('payment_date', [$from, $to])
            ->whereHas('employee.branch', fn($q) => $q->where('type', 'training'))
            ->orderByDesc('payment_date')
            ->get();

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
