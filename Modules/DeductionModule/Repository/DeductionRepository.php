<?php

namespace Modules\DeductionModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\DeductionModule\App\Http\Models\Deduction;

class DeductionRepository extends BaseRepository
{
    public function model()
    {
        return Deduction::class;
    }

    public function getByMonth(string $month, ?int $branchId = null, ?int $deptId = null)
    {
        $query = Deduction::with(['employee.branch', 'employee.department'])
            ->where('month', $month);

        if ($branchId) {
            $query->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));
        }

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        return $query->orderBy('created_at')->get();
    }

    public function createDeduction(array $data): Deduction
    {
        return Deduction::create($data);
    }

    public function updateDeduction(int $id, array $data): Deduction
    {
        $deduction = Deduction::findOrFail($id);
        $deduction->update($data);
        return $deduction->fresh();
    }

    public function deleteDeduction(int $id): void
    {
        Deduction::findOrFail($id)->delete();
    }

    public function findDeduction(int $id): Deduction
    {
        return Deduction::with(['employee.branch', 'employee.department'])->findOrFail($id);
    }
}
