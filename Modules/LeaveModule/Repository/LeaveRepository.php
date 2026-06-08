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

    function filter($request)
    {
        return Leave::filter($request);
    }

    public function sumDaysByEmployeeAndMonth(int $employeeId, string $month): int
    {
        return (int) Leave::where('employee_id', $employeeId)
                          ->where('month', $month)
                          ->sum('days');
    }
}
