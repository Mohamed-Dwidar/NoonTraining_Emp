<?php

namespace Modules\LeaveModule\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\LeaveModule\Repository\LeaveRepository;

class LeaveService {

    protected LeaveRepository $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository) {
        $this->leaveRepository = $leaveRepository;
    }

    public function filter($request) {
        return $this->leaveRepository->filter($request);
    }

    public function find(int $id) {
        return $this->leaveRepository->find($id);
    }

    public function create(array $data) {
        return $this->leaveRepository->create([
            'employee_id' => $data['employee_id'],
            'type'        => $data['type'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'month'       => substr($data['start_date'], 0, 7),
            'days'        => $this->calcDays($data['start_date'], $data['end_date']),
            'reason'      => $data['reason'],
        ]);
    }

    public function update(array $data) {
        return $this->leaveRepository->update([
            'employee_id' => $data['employee_id'],
            'type'        => $data['type'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'month'       => substr($data['start_date'], 0, 7),
            'days'        => $this->calcDays($data['start_date'], $data['end_date']),
            'reason'      => $data['reason'],
        ], $data['id']);
    }

    public function delete(int $id): void {
        $this->leaveRepository->delete($id);
    }

    private function calcDays(string $start, string $end): int {
        return max(1, Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1);
    }
}
