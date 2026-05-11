<?php

namespace Modules\PermissionModule\Services;

use Modules\PermissionModule\Repository\PermissionRepository;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function create($data)
    {
        return $this->permissionRepository->create($data);
    }

    public function findAll()
    {
         return $this->permissionRepository->get();
        // permissionRepository->all()
    }

    public function findOne($id)
    {
        // return $this->permissionRepository->find($id);
    }

    public function update($data)
    {
        // return $permission;
    }

    public function deleteOne($id)
    {
        // return $this->permissionRepository->delete($id);
    }
}
