<?php

namespace Modules\AdminModule\Repository;

use Modules\AdminModule\app\Http\Models\Admin;
use Prettus\Repository\Eloquent\BaseRepository;

class AdminRepository extends BaseRepository
{
    function model()
    {
        return Admin::class;
    }

}
