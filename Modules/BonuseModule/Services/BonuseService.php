<?php

namespace Modules\BonuseModule\Services;

use Illuminate\Support\Collection;
use Modules\BonuseModule\Repository\BonuseRepository;

class BonuseService {

    protected BonuseRepository $bonuseRepository;

    public function __construct(BonuseRepository $bonuseRepository) {
        $this->bonuseRepository = $bonuseRepository;
    }

    /**
     * Get all students
     */
    public function findAll() {
        return $this->bonuseRepository->all();
    }

    public function paginate($perPage = 15) {
        return $this->bonuseRepository->paginate($perPage);
    }

    public function filter($request = []) {
        return $this->bonuseRepository->filter($request);
    }

    /**
     * Get a single student
     */
    public function find($id) {
        return $this->bonuseRepository->find($id);
    }

    public function findByNationalId($nationalId) {
        return $this->bonuseRepository->findByNationalId($nationalId);
    }

    /**
     * Create new student
     */
    public function create(array $data) {
        return $this->bonuseRepository->create([
            'employee_id' => $data['employee_id'],
            'month'       => $data['month'],
            'amount'      => $data['amount'],
            'reason'      => $data['reason'] ?? null,
        ]);
    }

    /**
     * Update student
     */
    public function update(array $data) {
        $updateData = [
            'employee_id' => $data['employee_id'],
            'month'       => $data['month'],
            'amount'      => $data['amount'],
            'reason'      => $data['reason'] ?? null,
        ];
        return $this->bonuseRepository->update($updateData, $data['id']);
    }

    /**
     * Delete student
     */
    public function delete($id) {
        return $this->bonuseRepository->delete($id);
    }
}
