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

    function filter($request) {
        return Deduction::filter($request);
    }

    public function sumByEmployeeAndMonth(int $employeeId, string $month): float
    {
        return (float) Deduction::where('employee_id', $employeeId)
                                ->where('month', $month)
                                ->sum('amount');
    }

    public function countViolationRepeats(int $employeeId, int $violationId, string $month, ?int $excludeId = null): int
    {
        $query = Deduction::where('employee_id', $employeeId)
            ->where('violation_id', $violationId)
            ->where('month', $month)
            ->where('type', 'violation');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count();
    }
}
