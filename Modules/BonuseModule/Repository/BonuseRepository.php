<?php

namespace Modules\BonuseModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\BonuseModule\App\Http\Models\Bonuse;

class BonuseRepository extends BaseRepository
{
    public function model()
    {
        return Bonuse::class;
    }

    public function getByMonth(string $month, ?int $branchId = null, ?int $deptId = null)
    {
        $query = Bonuse::with(['employee.branch', 'employee.department'])
            ->where('month', $month);

        if ($branchId) {
            $query->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));
        }

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        return $query->orderBy('created_at')->get();
    }

    public function createBonuse(array $data): Bonuse
    {
        return Bonuse::create($data);
    }

    public function updateBonuse(int $id, array $data): Bonuse
    {
        $bonuse = Bonuse::findOrFail($id);
        $bonuse->update($data);
        return $bonuse->fresh();
    }

    public function deleteBonuse(int $id): void
    {
        Bonuse::findOrFail($id)->delete();
    }

    public function findBonuse(int $id): Bonuse
    {
        return Bonuse::with(['employee.branch', 'employee.department'])->findOrFail($id);
    }
}
