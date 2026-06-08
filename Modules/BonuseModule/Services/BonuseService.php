<?php

namespace Modules\BonuseModule\Services;

use Illuminate\Support\Collection;
use Modules\BonuseModule\Repository\BonuseRepository;
use Modules\PayrollModule\Repository\PayrollRepository;

class BonuseService {

    protected BonuseRepository $bonuseRepository;
    protected PayrollRepository $payrollRepository;

    public function __construct(BonuseRepository $bonuseRepository, PayrollRepository $payrollRepository) {
        $this->bonuseRepository = $bonuseRepository;
        $this->payrollRepository = $payrollRepository;
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
        $bonuse = $this->bonuseRepository->create([
            'employee_id' => $data['employee_id'],
            'month'       => $data['month'],
            'amount'      => $data['amount'],
            'reason'      => $data['reason'] ?? null,
        ]);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($data['employee_id'], $data['month']);

        return $bonuse;
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

        $bounuse = $this->bonuseRepository->update($updateData, $data['id']);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($data['employee_id'], $data['month']);

        return $bounuse;
    }

    /**
     * Delete student
     */
    public function delete($id) {
        $oldBonuse = $this->find($id);
        $delete = $this->bonuseRepository->delete($id);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($oldBonuse->employee_id, $oldBonuse->month);

        return $delete;
    }
}
