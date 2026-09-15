<?php

namespace Modules\CommissionModule\Services;

use Modules\CommissionModule\Repository\CommissionRepository;

class CommissionService {

    protected $commissionRepository;

    public function __construct(CommissionRepository $commissionRepository) {
        $this->commissionRepository = $commissionRepository;
    }

    public function getAllCommissions() {
        return $this->commissionRepository->all();
    }

    public function findOne($id) {
        return $this->commissionRepository->find($id);
    }

    public function create(array $data) {
        return $this->commissionRepository->create([
            'name'  => $data['name'],
            'type'  => $data['type'],
            'value' => $data['value'],
        ]);
    }

    public function update(int $id, array $data) {
        return $this->commissionRepository->update([
            'name'  => $data['name'],
            'type'  => $data['type'],
            'value' => $data['value'],
        ], $id);
    }

    public function deleteCommission($id) {
        return $this->commissionRepository->delete($id);
    }

    public function filter($data = []) {
        return $this->commissionRepository->filter($data);
    }
}
