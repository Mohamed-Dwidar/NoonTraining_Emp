<?php

namespace Modules\AdminModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\AdminModule\app\Http\Models\Admin;

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
        $admin = Admin::create([
            'name' => "admin",
            'email' => 'admin@noontraining.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
