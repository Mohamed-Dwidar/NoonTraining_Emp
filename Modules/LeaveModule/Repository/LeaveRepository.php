<?php

namespace Modules\LeaveModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\LeaveModule\App\Http\Models\Leave;

class LeaveRepository extends BaseRepository
{
    public function model()
    {
        return Leave::class;
    }

    public function getByMonth(string $month, ?int $branchId = null, ?int $deptId = null)
    {
        $query = Leave::with(['employee.branch', 'employee.department'])
            ->where('month', $month);

        if ($branchId) {
            $query->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));
        }

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        return $query->orderBy('start_date')->get();
    }

    public function createLeave(array $data): Leave
    {
        return Leave::create($data);
    }

    public function updateLeave(int $id, array $data): Leave
    {
        $leave = Leave::findOrFail($id);
        $leave->update($data);
        return $leave->fresh();
    }

    public function deleteLeave(int $id): void
    {
        Leave::findOrFail($id)->delete();
    }

    public function findLeave(int $id): Leave
    {
        return Leave::with(['employee.branch', 'employee.department'])->findOrFail($id);
    }
}
