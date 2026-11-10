<?php

namespace Modules\PermissionModule\Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionModuleDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PermissionSeederTableSeeder::class);
    }
}
