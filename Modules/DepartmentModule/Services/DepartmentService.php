<?php

namespace Modules\DepartmentModule\Services;

use App\Helpers\UploaderHelper;
use Modules\DepartmentModule\Repository\DepartmentRepository;

class DepartmentService {

    protected $departmentRepository;
    use UploaderHelper;

    public function __construct(DepartmentRepository $departmentRepository) {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAllDepartments() {
        return $this->departmentRepository->all();
    }

    public function findWhere($arr) {
        return $this->departmentRepository->findWhere($arr);
    }

    public function findOne($id) {
        return $this->departmentRepository->findWhere(['id' => $id])->first();
    }

    public function getDepartmentById($id) {
        return $this->departmentRepository->find($id);
    }

    public function create($data) {
        $departmentData = [
            'branch_id' => $data['branch_id'],
            'name' => $data['name']
        ];

        $department = $this->departmentRepository->create($departmentData);
        return $department;
    }

    public function update($data) {
        $id = $data['id'];
        $department = $this->departmentRepository->find($id);
        if (!$department) {
            return null; // Handle case where department is not found
        }

        // Update department data
        $departmentData = [
            'branch_id' => $data['branch_id'],
            'name' => $data['name'] ?? $department->name
        ];

        $this->departmentRepository->update($departmentData, $id);

        return $department;
    }

    public function deleteDepartment($id) {
        return $this->departmentRepository->delete($id);
    }

    public function filter($data = []) {
        return $this->departmentRepository->filter($data);
    }
}
