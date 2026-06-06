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

    public function getLeaves(string $month, ?int $branchId = null, ?int $deptId = null): Collection {
        return $this->leaveRepository->getByMonth($month, $branchId, $deptId);
    }

    public function findLeave(int $id) {
        return $this->leaveRepository->findLeave($id);
    }

    public function createLeave(array $data) {
        $data['month'] = substr($data['start_date'], 0, 7);
        $data['days']  = $this->calcDays($data['start_date'], $data['end_date']);
        return $this->leaveRepository->createLeave($data);
    }

    public function updateLeave(int $id, array $data) {
        $data['month'] = substr($data['start_date'], 0, 7);
        $data['days']  = $this->calcDays($data['start_date'], $data['end_date']);
        return $this->leaveRepository->updateLeave($id, $data);
    }

    public function deleteLeave(int $id): void {
        $this->leaveRepository->deleteLeave($id);
    }

    private function calcDays(string $start, string $end): int {
        return max(1, Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1);
    }
}
