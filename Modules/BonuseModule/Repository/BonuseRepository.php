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

    function filter($request) {
        return Bonuse::filter($request);
    }

    public function sumByEmployeeAndMonth(int $employeeId, string $month): float
    {
        return (float) Bonuse::where('employee_id', $employeeId)
                             ->where('month', $month)
                             ->sum('amount');
    }
}
