<?php

namespace Modules\UserModule\Repository;

use Modules\UserModule\app\Http\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{
    function model()
    {
        return User::class;
    }

}
