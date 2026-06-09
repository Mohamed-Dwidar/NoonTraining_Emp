<?php

namespace Modules\SettingModule\app\Repositories;

use Modules\SettingModule\app\Models\Setting;
use Prettus\Repository\Eloquent\BaseRepository;

class SettingRepository extends BaseRepository {

    public function model() {
        return Setting::class;
    }
}
