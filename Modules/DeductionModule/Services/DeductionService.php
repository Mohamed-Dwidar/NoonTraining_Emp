<?php

namespace Modules\DeductionModule\Services;

use Illuminate\Support\Collection;
use Modules\DeductionModule\Repository\DeductionRepository;

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
}
