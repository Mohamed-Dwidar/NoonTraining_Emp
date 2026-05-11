<?php

namespace Modules\PermissionModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear old permissions
        DB::table('permissions')->truncate();

        // Define permissions (key => label)
        $permissions = [
            'create_exam' => 'إنشاء اختبار',
            'create_questions' => 'إنشاء أسئلة',
            'create_student' => 'إنشاء طالب',
        ];

        // Insert permissions into the DB
        foreach ($permissions as $name => $label) {
            DB::table('permissions')->insert([
                'name' => $name,     // internal name
                'label' => $label,   // human-readable label
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // DB::table('roles')->truncate();
        // DB::table('role_has_permissions')->truncate();

        $roles = [
            'Admin' => ['create_exam', 'create_questions', 'create_student'],
            'Moderator' => ['create_exam', 'create_questions', 'create_student'], 
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => $roleName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($rolePermissions as $permissionName) {
                $permissionId = DB::table('permissions')->where('name', $permissionName)->value('id');
                if ($permissionId) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
