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
            'stu_commission'       => $data['stu_commission']   ?? 0,
            'hired_at'             => $data['hired_at']         ?? null,
            'contract_ends_at'     => $data['contract_ends_at'] ?? null,
            'terminated_at'        => $data['terminated_at']    ?? null,
        ]);

        $employee->user()->create([
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
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
            'stu_commission'       => $data['stu_commission']   ?? $employee->stu_commission,
            'hired_at'             => $data['hired_at']         ?? $employee->hired_at,
            'contract_ends_at'     => $data['contract_ends_at'] ?? $employee->contract_ends_at,
            'terminated_at'        => $data['terminated_at']    ?? $employee->terminated_at,
        ], $id);

        $userUpdate = [];
        if (!empty($data['email'])) {
            $userUpdate['email'] = $data['email'];
        }
        if (!empty($data['change_password']) && !empty($data['password'])) {
            $userUpdate['password'] = bcrypt($data['password']);
        }
        if (!empty($userUpdate)) {
            $employee->user()->update($userUpdate);
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
        if ($status === 'resigned' || $status === 'terminated') { //resigned or terminated
            $terminated_at = now();
        } else {
            $terminated_at = null;
        }
        return $this->employeeRepository->update(['terminated_at' => $terminated_at, 'status' => $status], $id);
    }

    public function updateCommission($id, $commission) {
        return $this->employeeRepository->update(['stu_commission' => $commission], $id);
    }
}
