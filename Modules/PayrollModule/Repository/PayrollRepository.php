<?php

namespace Modules\PayrollModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\PayrollModule\App\Http\Models\Payroll;
use Modules\EmployeeModule\App\Http\Models\Employee;
use Modules\EmployeeModule\App\Http\Models\EmployeeCommission;
use Modules\CommissionModule\App\Http\Models\Commission;
use Modules\DeductionModule\App\Http\Models\Deduction;
use Modules\BonuseModule\App\Http\Models\Bonuse;
use Modules\LeaveModule\App\Http\Models\Leave;
use Modules\StudentModule\App\Http\Models\Student;

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

        $studentsCommission = (float) array_sum(array_column(
            $this->studentsCommissionBreakdown($employeeId, $month),
            'total'
        ));

        $totalDeductions = $fixedDeductions + $leaveDeductions;
        $daysAbsent      = $leaveDays;
        $daysPresent     = max(0, $monthlyWorkingDays - $daysAbsent);
        $totalSalary     = max(0, $basicSalary - $totalDeductions + $bonuses + $studentsCommission);

        return $this->updateRecord($employeeId, $month, [
            'monthly_working_days' => $monthlyWorkingDays,
            'days_present'         => $daysPresent,
            'days_absent'          => $daysAbsent,
            'days_late'            => 0,
            'basic_salary'         => $basicSalary,
            'deductions'           => $totalDeductions,
            'bonuses'              => $bonuses,
            'students_commission'  => $studentsCommission,
            'total_salary'         => $totalSalary,
        ]);
    }

    /**
     * Students commission grouped by commission for one employee/month.
     * Each row: name, type, value, students_count, total.
     */
    public function studentsCommissionBreakdown(int $employeeId, string $month): array {
        // Commission applies only to students who paid in the active payroll month.
        // $students = Student::where('employee_id', $employeeId)
        //                     ->paidInMonth($month)
        //                     ->get();

        // Commission applies only to students who registered in the active payroll month.
        $students = Student::where('employee_id', $employeeId)
                            ->registeredInMonth($month)
                            ->get();

        $amounts = $this->studentsCommissionAmounts($students);

        $breakdown = [];
        foreach ($students as $student) {
            $row = $amounts[$student->id] ?? null;

            if (!$row) {
                continue;
            }

            $breakdown[$student->commission_id] ??= [
                'name'           => $row['name'],
                'type'           => $row['type'],
                'value'          => $row['value'],
                'students_count' => 0,
                'total'          => 0.0,
            ];
            $breakdown[$student->commission_id]['students_count']++;
            $breakdown[$student->commission_id]['total'] += $row['amount'];
        }

        return array_values($breakdown);
    }

    /**
     * Commission earned per student, keyed by student id.
     * Students may belong to different employees. A student with no matching rate is left out.
     * Each row: name, type, value, amount.
     */
    public function studentsCommissionAmounts(iterable $students): array {
        $students      = collect($students);
        $commissionIds = $students->pluck('commission_id')->filter()->unique();

        // Employee-specific overrides take priority; fall back to the commission's default rate.
        $employeeRates = EmployeeCommission::whereIn('employee_id', $students->pluck('employee_id')->filter()->unique())
                                            ->whereIn('commission_id', $commissionIds)
                                            ->get()
                                            ->keyBy(fn($r) => $r->employee_id . '-' . $r->commission_id);

        $defaultRates = Commission::whereIn('id', $commissionIds)
                                   ->get()
                                   ->keyBy('id');

        $amounts = [];
        foreach ($students as $student) {
            $rate = $employeeRates->get($student->employee_id . '-' . $student->commission_id)
                 ?? $defaultRates->get($student->commission_id);

            if (!$rate) {
                continue;
            }

            $amounts[$student->id] = [
                'name'   => $defaultRates->get($student->commission_id)?->name ?? '—',
                'type'   => $rate->type,
                'value'  => (float) $rate->value,
                'amount' => $rate->type === 'percentage'
                    ? (float) $student->paid_amount * ((float) $rate->value / 100)
                    : (float) $rate->value,
            ];
        }

        return $amounts;
    }

    public function filter(array $request = []) {
        return Payroll::filter($request);
    }
}
