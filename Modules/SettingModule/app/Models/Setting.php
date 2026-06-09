<?php

namespace Modules\SettingModule\app\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    protected $guarded = [];
    protected $table = 'settings';
    public $timestamps = false;

}
