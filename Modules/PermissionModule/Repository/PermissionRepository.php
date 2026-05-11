<?php

namespace Modules\PermissionModule\Repository;

use Modules\PermissionModule\Entities\Permission;
use Prettus\Repository\Eloquent\BaseRepository;


class PermissionRepository extends BaseRepository
{
    function model()
    {
        return Permission::class;
    }

    function filter($request)
    {
        return Permission::filter($request);
    }

}
