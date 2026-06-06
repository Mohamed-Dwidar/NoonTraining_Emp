<?php

namespace Modules\AttendanceModule\Services;

use Illuminate\Support\Collection;
use Modules\AttendanceModule\App\Http\Models\Attendance;
use Modules\AttendanceModule\Repository\AttendanceRepository;

class AttendanceService {
    protected AttendanceRepository $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepository) {
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * For each employee ensure a record exists for the given month.
     * Does nothing if the record already exists.
     */
    public function ensureMonthlyRecords(Collection $employees, string $monthDate): void {
        foreach ($employees as $employee) {
            $this->attendanceRepository->initRecord(
                $employee->id,
                $monthDate,
                [
                    'monthly_working_days' => $employee->monthly_working_days,
                    'days_present'         => $employee->monthly_working_days,
                    'days_absent'          => 0,
                    'attendance_rate'      => 100.00,
                    'status'               => Attendance::STATUS_REGULAR,
                ]
            );
        }
    }

    /**
     * Return attendance records for a branch/month, optionally filtered by department.
     */
    public function getMonthlyAttendances(string $monthDate, int $branchId, ?int $deptId = null): Collection {
        return $this->attendanceRepository->getByMonthAndBranch($monthDate, $branchId, $deptId);
    }

    /**
     * Bulk-save submitted attendance rows.
     * Computes days_absent, attendance_rate and status from the input.
     */
    public function saveMonthlyAttendances(array $records, string $monthDate): void {
        foreach ($records as $employeeId => $data) {
            $monthlyDays = max(1, (int) ($data['monthly_working_days'] ?? 1));
            $daysPresent = min($monthlyDays, max(0, (int) ($data['days_present'] ?? 0)));
            $daysAbsent  = $monthlyDays - $daysPresent;

            $this->attendanceRepository->upsert((int) $employeeId, $monthDate, [
                'monthly_working_days' => $monthlyDays,
                'days_present'         => $daysPresent,
                'days_absent'          => $daysAbsent,
                'attendance_rate'      => Attendance::computeRate($daysPresent, $monthlyDays),
                'status'               => Attendance::computeStatus($daysAbsent),
            ]);
        }
    }
}
