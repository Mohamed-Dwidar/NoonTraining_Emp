<?php

namespace Modules\AdminModule\Repository;

use Modules\AdminModule\App\Http\Models\Admin;
use Prettus\Repository\Eloquent\BaseRepository;

class AdminRepository extends BaseRepository
{
    function model()
    {
        return Admin::class;
    }

}
