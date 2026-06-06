<?php

namespace Modules\EmployeeModule\Services;

use App\Helpers\UploaderHelper;
use Modules\EmployeeModule\Repository\EmployeeRepository;

class EmployeeService {

    protected $employeeRepository;
    use UploaderHelper;

    public function __construct(EmployeeRepository $employeeRepository) {
        $this->employeeRepository = $employeeRepository;
    }

    public function getAllEmployees() {
        return $this->employeeRepository->all();
    }

    public function findWhere($arr) {
        return $this->employeeRepository->findWhere($arr);
    }

    public function findOne($id) {
        return $this->employeeRepository->findWhere(['id' => $id])->first();
    }

    public function getEmployeeById($id) {
        return $this->employeeRepository->find($id);
    }

    public function create($data) {
        $employeeData = [
            'branch_id'            => $data['branch_id'],
            'department_id'        => $data['department_id'],
            'name'                 => $data['name'],
            'job'                  => $data['job'],
            'basic_salary'         => $data['basic_salary'],
            'monthly_working_days' => $data['monthly_working_days'],
            'daily_working_hours'  => $data['daily_working_hours'],
        ];

        return $this->employeeRepository->create($employeeData);
    }

    public function update($data) {
        $id = $data['id'];
        $employee = $this->employeeRepository->find($id);
        if (!$employee) {
            return null;
        }

        $employeeData = [
            'branch_id'            => $data['branch_id'],
            'department_id'        => $data['department_id'],
            'name'                 => $data['name']                 ?? $employee->name,
            'job'                  => $data['job']                  ?? $employee->job,
            'basic_salary'         => $data['basic_salary']         ?? $employee->basic_salary,
            'monthly_working_days' => $data['monthly_working_days'] ?? $employee->monthly_working_days,
            'daily_working_hours'  => $data['daily_working_hours']  ?? $employee->daily_working_hours,
        ];

        $this->employeeRepository->update($employeeData, $id);

        return $employee;
    }

    public function deleteEmployee($id) {
        return $this->employeeRepository->delete($id);
    }

    public function filter($data = []) {
        return $this->employeeRepository->filter($data);
    }
}
