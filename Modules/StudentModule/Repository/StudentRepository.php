<?php

namespace Modules\StudentModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\StudentModule\App\Http\Models\Student;

class StudentRepository extends BaseRepository
{
    /** A student without a national_id counts as their own unique person. */
    private const DISTINCT_STUDENT_EXPR = "COUNT(DISTINCT COALESCE(NULLIF(national_id, ''), CONCAT('row-', id)))";

    /** payment_date is a free-text string column; skip malformed values (e.g. a raw Excel serial). */
    private const VALID_DATE_EXPR = "payment_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'";

    public function model()
    {
        return Student::class;
    }

    // public function getAll(?int $branchId = null, ?int $deptId = null, ?int $employeeId = null)
    // {
    //     $query = Student::with(['employee.branch', 'employee.department']);

    //     if ($branchId) {
    //         $query->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));
    //     }
    //     if ($deptId) {
    //         $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
    //     }
    //     if ($employeeId) {
    //         $query->where('employee_id', $employeeId);
    //     }

    //     return $query->orderByDesc('id')->get();
    // }

    // public function createStudent(array $data): Student
    // {
    //     return Student::create($data);
    // }

    // public function updateStudent(int $id, array $data): Student
    // {
    //     $student = Student::findOrFail($id);
    //     $student->update($data);
    //     return $student->fresh();
    // }

    // public function deleteStudent(int $id): void
    // {
    //     Student::findOrFail($id)->delete();
    // }

    // public function findStudent(int $id): Student
    // {
    //     return Student::with(['employee.branch', 'employee.department'])->findOrFail($id);
    // }

    function filter($request) {
        return Student::filter($request);
    }

    public function getByEmployeeAndMonth(int $employeeId, string $month)
    {
        return Student::where('employee_id', $employeeId)->where('month', $month)->get();
    }

    public function firstMonthForEmployee(int $employeeId): ?string
    {
        return Student::where('employee_id', $employeeId)->min('month');
    }

    /** national_id => count, only for national IDs used more than once. */
    public function duplicateNationalIdCounts()
    {
        return Student::whereNotNull('national_id')
            ->where('national_id', '!=', '')
            ->selectRaw('national_id, COUNT(*) as cnt')
            ->groupBy('national_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('cnt', 'national_id');
    }

    /**
     * Students of training-branch employees with a valid payment_date in [$from, $to].
     * Used by ReportModule (performance metrics).
     * $filters: employee_id, branch_id, department_id (all optional).
     */
    public function trainingQueryByPaymentDate(string $from, string $to, array $filters = [])
    {
        return Student::query()
            ->whereRaw(self::VALID_DATE_EXPR)
            ->whereBetween('payment_date', [$from, $to])
            ->whereHas('employee.branch', fn($q) => $q->where('type', 'training'))
            ->when(!empty($filters['employee_id']), fn($q) => $q->where('employee_id', $filters['employee_id']))
            ->when(!empty($filters['branch_id']), fn($q) => $q->whereHas('employee', fn($q2) => $q2->where('branch_id', $filters['branch_id'])))
            ->when(!empty($filters['department_id']), fn($q) => $q->whereHas('employee', fn($q2) => $q2->where('department_id', $filters['department_id'])));
    }

    /** Per-employee students/admissions/revenue rows. */
    public function performanceByEmployee(string $from, string $to, array $filters = [])
    {
        return $this->trainingQueryByPaymentDate($from, $to, $filters)
            ->selectRaw('employee_id,'
                . self::DISTINCT_STUDENT_EXPR . ' as students_count,'
                . 'COUNT(*) as admissions_count,'
                . 'COALESCE(SUM(paid_amount), 0) as revenue')
            ->groupBy('employee_id')
            ->get();
    }

    /** One aggregate row (students_count, admissions_count, revenue). */
    public function performanceTotals(string $from, string $to, array $filters = [])
    {
        return $this->trainingQueryByPaymentDate($from, $to, $filters)
            ->selectRaw(self::DISTINCT_STUDENT_EXPR . ' as students_count,'
                . 'COUNT(*) as admissions_count,'
                . 'COALESCE(SUM(paid_amount), 0) as revenue')
            ->first();
    }

    /** The underlying registration rows, newest payment first. */
    public function trainingRecordsByPaymentDate(string $from, string $to, array $filters = [])
    {
        return $this->trainingQueryByPaymentDate($from, $to, $filters)
            ->orderByDesc('payment_date')
            ->get();
    }

    /** Delete many students at once (optionally only those of one employee); returns the number deleted. */
    public function deleteByIds(array $ids, ?int $employeeId = null): int
    {
        return Student::whereIn('id', $ids)
            ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
            ->delete();
    }
}
