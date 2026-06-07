<?php

namespace Modules\BonuseModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\BonuseModule\App\Http\Models\Bonuse;

class BonuseRepository extends BaseRepository
{
    public function model()
    {
        return Bonuse::class;
    }

    function filter($request) {
        return Bonuse::filter($request);
    }


}
