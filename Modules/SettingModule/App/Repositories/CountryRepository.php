<?php

namespace Modules\SettingModule\app\Repositories;

use Modules\SettingModule\app\Models\Country;
use Prettus\Repository\Eloquent\BaseRepository;

class CountryRepository extends BaseRepository {

    public function model() {
        return Country::class;
    }

    public function getCountryIdByPhoneCode($phoneCode) {
        $country = $this->findWhere(['phone_code' => $phoneCode])->first();
        if ($country) {
            return $country->id;
        }
        return 1; // default country id
    }
}
