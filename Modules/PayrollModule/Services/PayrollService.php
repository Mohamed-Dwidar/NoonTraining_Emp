<?php

namespace Modules\PayrollModule\Services;

use Illuminate\Support\Collection;
use Modules\PayrollModule\Repository\PayrollRepository;
use Modules\BonuseModule\Repository\BonuseRepository;
use Modules\DeductionModule\Repository\DeductionRepository;
use Modules\EmployeeModule\Repository\EmployeeRepository;
use Modules\LeaveModule\Repository\LeaveRepository;

class PayrollService {
    protected PayrollRepository $payrollRepository;
    protected EmployeeRepository $employeeRepository;
    protected DeductionRepository $deductionRepository;
    protected BonuseRepository $bonuseRepository;
    protected LeaveRepository $leaveRepository;

    public function __construct(
        PayrollRepository $payrollRepository,
        EmployeeRepository $employeeRepository,
        DeductionRepository $deductionRepository,
        BonuseRepository $bonuseRepository,
        LeaveRepository $leaveRepository
    ) {
        $this->payrollRepository   = $payrollRepository;
        $this->employeeRepository  = $employeeRepository;
        $this->deductionRepository = $deductionRepository;
        $this->bonuseRepository    = $bonuseRepository;
        $this->leaveRepository     = $leaveRepository;
    }

    public function filter($request = []) {
        return $this->payrollRepository->filter($request);
    }

    public function findOne($id) {
        return $this->payrollRepository->find($id);
    }

    /**
     * For each employee ensure a record exists for the given month.
     * Does nothing if the record already exists.
     */
    public function ensureMonthlyRecords(Collection $employees, string $monthDate): void {
        foreach ($employees as $employee) {
            $this->recalculateForEmployee($employee->id, $monthDate);
        }
    }

    /**
     * Recalculate and persist the payroll record for one employee/month.
     * Call this whenever deductions, bonuses, or leaves change for the employee.
     *
     * Deductions  = fixed deductions (DeductionModule) + leave deductions (days × daily_salary)
     * Bonuses     = BonuseModule total for the month
     * total_salary = basic_salary - total_deductions + bonuses  (floored at 0)
     */
    public function recalculateForEmployee(int $employeeId, string $month): void {
        $this->payrollRepository->recalculateForEmployee($employeeId, $month);
    }
}
