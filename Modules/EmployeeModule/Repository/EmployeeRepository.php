<?php

namespace Modules\EmployeeModule\Repository;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\EmployeeModule\App\Http\Models\Employee;
use Modules\EmployeeModule\App\Http\Models\EmployeeCommission;

class EmployeeRepository  extends BaseRepository
{
    function model()
    {
        return Employee::class;
    }

    function filter($request)
    {
        return Employee::filter($request);
    }

    /** Employees by id incl. soft-deleted, with branch/department, keyed by id. */
    public function findWithTrashedByIds($ids)
    {
        return Employee::withTrashed()->with('branch', 'department')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');
    }

    public function deleteCommissions(int $employeeId): void
    {
        EmployeeCommission::where('employee_id', $employeeId)->delete();
    }

    public function saveCommission(int $employeeId, int $commissionId, string $type, $value): void
    {
        EmployeeCommission::updateOrCreate(
            ['employee_id' => $employeeId, 'commission_id' => $commissionId],
            ['type' => $type, 'value' => $value]
        );
    }
}
