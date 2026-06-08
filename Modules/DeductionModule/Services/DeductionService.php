<?php

namespace Modules\DeductionModule\Services;

use Illuminate\Support\Collection;
use Modules\DeductionModule\Repository\DeductionRepository;
use Modules\ViolationModule\App\Http\Models\ViolationRepeat;
use Modules\PayrollModule\Repository\PayrollRepository;

class DeductionService {

    protected DeductionRepository $deductionRepository;
    protected PayrollRepository $payrollRepository;

    public function __construct(DeductionRepository $deductionRepository, PayrollRepository $payrollRepository) {
        $this->deductionRepository = $deductionRepository;
        $this->payrollRepository = $payrollRepository;
    }

    /**
     * Get all deductions
     */
    public function findAll() {
        return $this->deductionRepository->all();
    }

    public function paginate($perPage = 15) {
        return $this->deductionRepository->paginate($perPage);
    }

    public function filter($request = []) {
        return $this->deductionRepository->filter($request);
    }

    /**
     * Get a single deduction
     */
    public function find($id) {
        return $this->deductionRepository->find($id);
    }

    public function create(array $data) {
        $deduction = $this->deductionRepository->create([
            'type'         => $data['type'] ?? 'general',
            'employee_id' => $data['employee_id'],
            'month'        => $data['month'],
            'violation_id' => $data['violation_id'] ?? null,
            'violation_repeat_number' => $data['violation_repeat_number'] ?? null,
            'amount'       => $data['amount'],
            'reason'       => $data['reason'] ?? null,
        ]);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($data['employee_id'], $data['month']);

        return $deduction;
    }

    public function update(array $data) {
        $updateData = [
            'type'         => $data['type'] ?? 'general',
            'employee_id' => $data['employee_id'],
            'month'        => $data['month'],
            'violation_id' => $data['violation_id'] ?? null,
            'violation_repeat_number' => $data['violation_repeat_number'] ?? null,
            'amount'       => $data['amount'],
            'reason'       => $data['reason'] ?? null,
        ];
        $deduction = $this->deductionRepository->update(
            $updateData,
            $data['id']
        );

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($data['employee_id'], $data['month']);

        return $deduction;
    }
    public function delete(int $id): void {
        $oldDeduction = $this->find($id);
        $delete = $this->deductionRepository->delete($id);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($oldDeduction->employee_id, $oldDeduction->month);
    }

    public function resolveViolationDeduction(int $employeeId, int $violationId, string $month, ?int $excludeId = null): array {
        $count        = $this->deductionRepository->countViolationRepeats($employeeId, $violationId, $month, $excludeId);
        $repeatNumber = $count + 1;

        $tiers  = ViolationRepeat::where('violation_id', $violationId)->orderBy('repeat_number')->get();
        $amount = 0;

        if ($tiers->isNotEmpty()) {
            $tier   = $tiers->firstWhere('repeat_number', $repeatNumber) ?? $tiers->last();
            $amount = $tier->deduction_amount;
        }

        return [
            'repeat_number' => $repeatNumber,
            'amount'        => $amount,
        ];
    }
}
