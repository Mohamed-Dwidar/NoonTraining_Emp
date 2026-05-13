<?php

namespace Modules\BranchModule\Repository;
use Prettus\Repository\Eloquent\BaseRepository;
use Modules\BranchModule\App\Http\Models\Branch;

class BranchRepository  extends BaseRepository
{
    function model()
    {
        return Branch::class;
    }

    function filter($request)
    {
        return Branch::filter($request);
    }

}
