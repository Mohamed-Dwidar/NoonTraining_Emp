<?php

namespace Modules\EmployeeModule\Repository;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\EmployeeModule\App\Http\Models\Employee;

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

}
