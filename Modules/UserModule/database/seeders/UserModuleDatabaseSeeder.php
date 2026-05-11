<?php

namespace Modules\UserModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class UserModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        Permission::create(['name' => 'show reports']);


    }
}
