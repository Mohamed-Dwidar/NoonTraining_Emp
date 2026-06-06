<?php

namespace Modules\BonuseModule\Services;

use Illuminate\Support\Collection;
use Modules\BonuseModule\Repository\BonuseRepository;

class BonuseService {

    protected BonuseRepository $bonuseRepository;

    public function __construct(BonuseRepository $bonuseRepository) {
        $this->bonuseRepository = $bonuseRepository;
    }

    public function getBonuses(string $month, ?int $branchId = null, ?int $deptId = null): Collection {
        return $this->bonuseRepository->getByMonth($month, $branchId, $deptId);
    }

    public function findBonuse(int $id) {
        return $this->bonuseRepository->findBonuse($id);
    }

    public function createBonuse(array $data) {
        return $this->bonuseRepository->createBonuse($data);
    }

    public function updateBonuse(int $id, array $data) {
        return $this->bonuseRepository->updateBonuse($id, $data);
    }

    public function deleteBonuse(int $id): void {
        $this->bonuseRepository->deleteBonuse($id);
    }
}
