<?php

namespace Modules\AdminModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\AdminModule\app\Http\Models\Admin;
use Modules\UserModule\App\Http\Models\User;

class AdminModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();


        DB::table('admins')->truncate();
        Admin::create([
            'name' => "admin",
        ]);

        User::create([
            'email' => "superAdmin@noontraining.com",
            'password' => bcrypt('123456'),
            'userable_type' => 'Modules\AdminModule\App\Http\Models\Admin',
            'userable_id' => 1,
        ]);

    }
}
