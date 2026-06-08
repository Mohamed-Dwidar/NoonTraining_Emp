<?php

namespace Modules\LeaveModule\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\LeaveModule\Repository\LeaveRepository;
use Modules\PayrollModule\Repository\PayrollRepository;

class LeaveService {

    protected LeaveRepository $leaveRepository;
    protected PayrollRepository $payrollRepository;

    public function __construct(LeaveRepository $leaveRepository, PayrollRepository $payrollRepository) {
        $this->leaveRepository = $leaveRepository;
        $this->payrollRepository = $payrollRepository;
    }

    public function filter($request) {
        return $this->leaveRepository->filter($request);
    }

    public function find(int $id) {
        return $this->leaveRepository->find($id);
    }

    public function create(array $data) {
        $leave = $this->leaveRepository->create([
            'employee_id' => $data['employee_id'],
            'type'        => $data['type'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'month'       => substr($data['start_date'], 0, 7),
            'days'        => $this->calcDays($data['start_date'], $data['end_date']),
            'reason'      => $data['reason'],
        ]);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($data['employee_id'], substr($data['start_date'], 0, 7));

        return $leave;
    }

    public function update(array $data) {
        $leave = $this->leaveRepository->update([
            'employee_id' => $data['employee_id'],
            'type'        => $data['type'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'month'       => substr($data['start_date'], 0, 7),
            'days'        => $this->calcDays($data['start_date'], $data['end_date']),
            'reason'      => $data['reason'],
        ], $data['id']);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($data['employee_id'], substr($data['start_date'], 0, 7));

        return $leave;
    }

    public function delete(int $id): void {
        $oldLeave = $this->find($id);
        $this->leaveRepository->delete($id);

        // Recalculate the payroll for the employee
        $this->payrollRepository->recalculateForEmployee($oldLeave->employee_id, $oldLeave->month);
    }

    private function calcDays(string $start, string $end): int {
        return max(1, Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1);
    }
}
