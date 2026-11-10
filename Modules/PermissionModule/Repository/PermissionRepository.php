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

    public function idsByNames(array $names): array
    {
        return Permission::whereIn('name', $names)->pluck('id')->toArray();
    }

    /** Permissions of one guard (e.g. 'employee'), in creation order. */
    public function forGuard(string $guard)
    {
        return Permission::where('guard_name', $guard)->orderBy('id')->get();
    }
}
