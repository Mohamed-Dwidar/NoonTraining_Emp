<?php

namespace Modules\PayrollModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\PayrollModule\App\Http\Models\Payroll;
use Modules\EmployeeModule\App\Http\Models\Employee;
use Modules\DeductionModule\App\Http\Models\Deduction;
use Modules\BonuseModule\App\Http\Models\Bonuse;
use Modules\LeaveModule\App\Http\Models\Leave;

class PayrollRepository extends BaseRepository {
    public function model() {
        return Payroll::class;
    }

    public function initRecord(int $employeeId, string $monthDate, array $defaults): Payroll {
        return Payroll::firstOrCreate(
            ['employee_id' => $employeeId, 'month' => $monthDate],
            $defaults
        );
    }

    public function updateRecord(int $employeeId, string $month, array $data): Payroll {
        return Payroll::updateOrCreate(
            ['employee_id' => $employeeId, 'month' => $month],
            $data
        );
    }

    public function recalculateForEmployee(int $employeeId, string $month): Payroll {
        $employee = Employee::findOrFail($employeeId);

        $monthlyWorkingDays = (int) ($employee->monthly_working_days ?? 1);
        $basicSalary        = (float) $employee->basic_salary;
        $dailySalary        = $monthlyWorkingDays > 0 ? $basicSalary / $monthlyWorkingDays : 0;

        $fixedDeductions = (float) Deduction::where('employee_id', $employeeId)
                                            ->where('month', $month)
                                            ->sum('amount');

        $leaveDays       = (int) Leave::where('employee_id', $employeeId)
                                      ->where('month', $month)
                                      ->sum('days');

        $leaveDeductions = $leaveDays * $dailySalary;

        $bonuses         = (float) Bonuse::where('employee_id', $employeeId)
                                         ->where('month', $month)
                                         ->sum('amount');

        $totalDeductions = $fixedDeductions + $leaveDeductions;
        $daysAbsent      = $leaveDays;
        $daysPresent     = max(0, $monthlyWorkingDays - $daysAbsent);
        $totalSalary     = max(0, $basicSalary - $totalDeductions + $bonuses);

        return $this->updateRecord($employeeId, $month, [
            'monthly_working_days' => $monthlyWorkingDays,
            'days_present'         => $daysPresent,
            'days_absent'          => $daysAbsent,
            'days_late'            => 0,
            'basic_salary'         => $basicSalary,
            'deductions'           => $totalDeductions,
            'bonuses'              => $bonuses,
            'total_salary'         => $totalSalary,
        ]);
    }

    public function filter(array $request = []) {
        return Payroll::filter($request);
    }
}
