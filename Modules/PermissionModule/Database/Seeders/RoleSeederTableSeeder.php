<?php

namespace Modules\PermissionModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Role::create(['name' => 'Super Seller Admin']);
        // Role::create(['name' => 'seller']);
    }
}
