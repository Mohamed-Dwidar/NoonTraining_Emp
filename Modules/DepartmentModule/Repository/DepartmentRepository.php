<?php

namespace Modules\DepartmentModule\Repository;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\DepartmentModule\App\Http\Models\Department;

class DepartmentRepository  extends BaseRepository
{
    function model()
    {
        return Department::class;
    }

    function filter($request)
    {
        return Department::filter($request);
    }

}
