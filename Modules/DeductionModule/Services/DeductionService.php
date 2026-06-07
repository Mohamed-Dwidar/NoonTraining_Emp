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

    public function getDeductions(string $month, ?int $branchId = null, ?int $deptId = null): Collection {
        return $this->deductionRepository->getByMonth($month, $branchId, $deptId);
    }

    public function findDeduction(int $id) {
        return $this->deductionRepository->findDeduction($id);
    }

    public function createDeduction(array $data) {
        return $this->deductionRepository->createDeduction($data);
    }

    public function updateDeduction(int $id, array $data) {
        return $this->deductionRepository->updateDeduction($id, $data);
    }

    public function deleteDeduction(int $id): void {
        $this->deductionRepository->deleteDeduction($id);
    }

    public function resolveViolationDeduction(int $employeeId, int $violationId, string $month, ?int $excludeId = null): array
    {
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
