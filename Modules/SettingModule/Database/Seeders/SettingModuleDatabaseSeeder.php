<?php

namespace Modules\SettingModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingModuleDatabaseSeeder extends Seeder {
    public function run(): void {
        Model::unguard();

        DB::table('settings')->truncate();

        $rows = [
            //general
            ['emp_monthly_working_days', '24', 'أيام العمل الشهرية للموظف', 'general'],
            ['emp_daily_working_hours', '8', 'ساعات العمل اليومية للموظف', 'general'],

        ];

        $items = array_map(function ($r, $index) {
            return [
                'key'       => $r[0],
                'value'    => $r[1],
                'label'       => $r[2],
                'type'       => $r[3]
            ];
        }, $rows, array_keys($rows));

        DB::table('settings')->insert($items);
    }
}
