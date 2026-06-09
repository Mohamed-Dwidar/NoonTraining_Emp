<?php

namespace Modules\EmployeeModule\Services;

use Modules\EmployeeModule\Repository\EmployeeRepository;

class EmployeeService {

    protected EmployeeRepository $employeeRepository;

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
        $employee = $this->employeeRepository->create([
            'branch_id'            => $data['branch_id'],
            'department_id'        => $data['department_id'],
            'name'                 => $data['name'],
            'job'                  => $data['job'],
            'basic_salary'         => $data['basic_salary'],
            'monthly_working_days' => $data['monthly_working_days'],
            'daily_working_hours'  => $data['daily_working_hours'],
            'stu_commission'       => $data['stu_commission'] ?? 0,
        ]);

        $employee->user()->create([
            'email'    => $data['email'],
            'password' => bcrypt('123456'),
        ]);

        return $employee;
    }

    public function update($data) {
        $id       = $data['id'];
        $employee = $this->employeeRepository->find($id);
        if (!$employee) return null;

        $this->employeeRepository->update([
            'branch_id'            => $data['branch_id'],
            'department_id'        => $data['department_id'],
            'name'                 => $data['name']                 ?? $employee->name,
            'job'                  => $data['job']                  ?? $employee->job,
            'basic_salary'         => $data['basic_salary']         ?? $employee->basic_salary,
            'monthly_working_days' => $data['monthly_working_days'] ?? $employee->monthly_working_days,
            'daily_working_hours'  => $data['daily_working_hours']  ?? $employee->daily_working_hours,
            'stu_commission'       => $data['stu_commission']        ?? $employee->stu_commission,
        ], $id);

        if (!empty($data['email'])) {
            $employee->user()->update(['email' => $data['email']]);
        }

        return $employee;
    }

    public function deleteEmployee($id) {
        return $this->employeeRepository->delete($id);
    }

    public function filter($data = []) {
        return $this->employeeRepository->filter($data);
    }

    public function updateStatus($id, $status) {
        return $this->employeeRepository->update(['status' => $status], $id);
    }

    public function updateCommission($id, $commission) {
        return $this->employeeRepository->update(['stu_commission' => $commission], $id);
    }
}
