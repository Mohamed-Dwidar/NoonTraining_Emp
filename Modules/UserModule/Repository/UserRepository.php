<?php

namespace Modules\UserModule\Repository;

use Modules\UserModule\App\Http\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    // public function createForEmployee(int $employeeId, string $employeeClass, string $email, string $password = '123456'): User
    // {
    //     return User::forceCreate([
    //         'email'         => $email,
    //         'password'      => bcrypt($password),
    //         'userable_id'   => $employeeId,
    //         'userable_type' => $employeeClass,
    //     ]);
    // }

    // public function updateEmailForEmployee(int $employeeId, string $employeeClass, string $email): void
    // {
    //     User::where('userable_id', $employeeId)
    //         ->where('userable_type', $employeeClass)
    //         ->update(['email' => $email]);
    // }

}
