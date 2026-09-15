<?php

namespace Modules\CommissionModule\Repository;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\CommissionModule\App\Http\Models\Commission;

class CommissionRepository  extends BaseRepository
{
    function model()
    {
        return Commission::class;
    }

    function filter($request)
    {
        return Commission::filter($request);
    }

}
