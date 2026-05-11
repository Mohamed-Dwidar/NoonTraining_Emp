<?php

namespace Modules\UserModule\Services;

use App\Models\User;
use Modules\PermissionModule\Entities\Permission;
use Modules\UserModule\Repository\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($data)
    {
        $user_data = [
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password),
        ];
        $user = $this->userRepository->create($user_data);

        if (!empty($data['permissions'])) {
            $user->permissions()->sync(
                collect($data['permissions'])->mapWithKeys(function ($id) {
                    return [$id => ['model_type' => \Modules\UserModule\app\Http\Models\User::class]];
                })->toArray()
            );
        }

        return $user;
    }

    public function update($data)
    {
        $user_data = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $user_data['password'] = bcrypt($data['password']);
        }

        $user = $this->userRepository->update($user_data, $data['id']);

        if (!empty($data['permissions'])) {

            // Convert permission NAMES to permission IDs
            $permissionIds = Permission::whereIn('name', $data['permissions'])
                ->pluck('id')
                ->toArray();

            // Sync same way as create()
            $user->permissions()->sync(
                collect($permissionIds)->mapWithKeys(function ($id) {
                    return [$id => ['model_type' => \Modules\UserModule\app\Http\Models\User::class]];
                })->toArray()
            );
        } else {
            $user->permissions()->sync([]);
        }

        return $user;
    }





    public function findAll()
    {
        return $this->userRepository->get();
    }

    public function findOne($id)
    {
        return $this->userRepository->find($id);
    }


    public function deleteOne($id)
    {
        return $this->userRepository->delete($id);
    }

    public function updatePassword($data)
    {
        $new_password = bcrypt($data->password);
        return $this->userRepository->update(['password' => $new_password], $data->id);
    }
}
