<?php

namespace Modules\AttendanceModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\AttendanceModule\App\Http\Models\Attendance;

class AttendanceRepository extends BaseRepository
{
    public function model()
    {
        return Attendance::class;
    }

    public function initRecord(int $employeeId, string $monthDate, array $defaults): Attendance
    {
        return Attendance::firstOrCreate(
            ['employee_id' => $employeeId, 'month' => $monthDate],
            $defaults
        );
    }

    public function getByMonthAndBranch(string $monthDate, int $branchId, ?int $deptId = null)
    {
        $query = Attendance::with(['employee.branch', 'employee.department'])
            ->where('month', $monthDate)
            ->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        return $query->get();
    }

    public function upsert(int $employeeId, string $monthDate, array $data): Attendance
    {
        return Attendance::updateOrCreate(
            ['employee_id' => $employeeId, 'month' => $monthDate],
            $data
        );
    }

    public function filter($request)
    {
        return Attendance::filter($request);
    }
}
