<?php

namespace Modules\PayrollModule\Services;

use Illuminate\Support\Collection;
use Modules\PayrollModule\Repository\PayrollRepository;
use Modules\BonuseModule\Repository\BonuseRepository;
use Modules\DeductionModule\Repository\DeductionRepository;
use Modules\EmployeeModule\Repository\EmployeeRepository;
use Modules\LeaveModule\Repository\LeaveRepository;
use Modules\StudentModule\Repository\StudentRepository;

class PayrollService {
    protected PayrollRepository $payrollRepository;
    protected EmployeeRepository $employeeRepository;
    protected DeductionRepository $deductionRepository;
    protected BonuseRepository $bonuseRepository;
    protected LeaveRepository $leaveRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        PayrollRepository $payrollRepository,
        EmployeeRepository $employeeRepository,
        DeductionRepository $deductionRepository,
        BonuseRepository $bonuseRepository,
        LeaveRepository $leaveRepository,
        StudentRepository $studentRepository
    ) {
        $this->payrollRepository   = $payrollRepository;
        $this->employeeRepository  = $employeeRepository;
        $this->deductionRepository = $deductionRepository;
        $this->bonuseRepository    = $bonuseRepository;
        $this->leaveRepository     = $leaveRepository;
        $this->studentRepository   = $studentRepository;
    }

    public function filter($request = []) {
        return $this->payrollRepository->filter($request);
    }

    public function findOne($id) {
        return $this->payrollRepository->find($id);
    }

    public function studentsCommissionBreakdown(int $employeeId, string $month): array {
        return $this->payrollRepository->studentsCommissionBreakdown($employeeId, $month);
    }

    /**
     * Everything behind one employee's payroll for a month
     * (used by the details popup and details PDF in both portals).
     */
    public function monthDetails(int $employeeId, string $month): array {
        return [
            'bonuses'     => $this->bonuseRepository->getByEmployeeAndMonth($employeeId, $month),
            'deductions'  => $this->deductionRepository->getByEmployeeAndMonth($employeeId, $month),
            'leaves'      => $this->leaveRepository->getByEmployeeAndMonth($employeeId, $month),
            'students'    => $this->studentRepository->getByEmployeeAndMonth($employeeId, $month),
            'commissions' => $this->studentsCommissionBreakdown($employeeId, $month),
        ];
    }

    /**
     * Months from the employee's first student registration up to the current month.
     */
    public function monthsSinceFirstStudent(int $employeeId): array {
        $months       = [];
        $firstMonth   = $this->studentRepository->firstMonthForEmployee($employeeId);
        $currentMonth = now()->format('Y-m');
        for ($m = $firstMonth; $m && $m <= $currentMonth; $m = date('Y-m', strtotime($m . '-01 +1 month'))) {
            $months[] = $m;
        }
        return $months;
    }

    public function studentsCommissionAmounts(iterable $students): array {
        return $this->payrollRepository->studentsCommissionAmounts($students);
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
