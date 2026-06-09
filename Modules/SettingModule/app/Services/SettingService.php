<?php

namespace Modules\SettingModule\app\Services;

use App\Helpers\UploaderHelper;
use App\Helpers\ApiResponseHelper;

use Modules\SettingModule\app\Repositories\SettingRepository;

class SettingService {
    use ApiResponseHelper;
    private $settingRepository;

    use UploaderHelper;

    public function __construct(SettingRepository $settingRepository) {
        $this->settingRepository = $settingRepository;
    }

    public function getByKey($key) {
        return $this->settingRepository->findWhere(['key' => $key])->first();
    }

    public function create($data) {

        $setting_data = [
            'key' => $data->key,
            'value' => $data->value,
            'label' => $data->label,
        ];

        return $this->settingRepository->create($setting_data);
    }

    public function update($data) {
        $setting_data = [
            'key' => $data->key,
            'value' => $data->value,
            'label' => $data->label,
        ];

        $old_data = $this->settingRepository->find($data->id);
        return $this->settingRepository->update($setting_data, $data->id);
    }

    public function findAll() {
        return $this->settingRepository->get();
    }

    public function findOne($id) {
        return $this->settingRepository->findWhere(['id' => $id])->first();
    }

    public function deleteOne($id) {
        $old_data = $this->settingRepository->find($id);
        if (!empty($old_data)) {
            return $this->settingRepository->delete($id);
        }
    }

    public function deleteMany($arr_ids) {
        if (!empty($arr_ids)) {
            foreach ($arr_ids as $id) {
                $this->deleteOne($id);
            }
        }
    }

    public function filter($request) {
        return $this->settingRepository->filter($request);
    }
}
