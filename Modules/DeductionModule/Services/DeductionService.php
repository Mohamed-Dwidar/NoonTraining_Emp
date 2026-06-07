<?php

namespace Modules\DeductionModule\Services;

use Illuminate\Support\Collection;
use Modules\DeductionModule\Repository\DeductionRepository;
use Modules\ViolationModule\App\Http\Models\ViolationRepeat;

class DeductionService {

    protected DeductionRepository $deductionRepository;

    public function __construct(DeductionRepository $deductionRepository) {
        $this->deductionRepository = $deductionRepository;
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
        return $this->deductionRepository->create([
            'type'         => $data['type'] ?? 'general',
            'employee_id' => $data['employee_id'],
            'month'        => $data['month'],
            'violation_id' => $data['violation_id'] ?? null,
            'violation_repeat_number' => $data['violation_repeat_number'] ?? null,
            'amount'       => $data['amount'],
            'reason'       => $data['reason'] ?? null,
        ]);
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
        return $this->deductionRepository->update(
            $updateData,
            $data['id']
        );
    }
    public function delete(int $id): void {
        $this->deductionRepository->delete($id);
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
