<?php

namespace Modules\PermissionModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionModuleDatabaseSeeder extends Seeder
{
    public function run()
    {
        // ======================================
        // 1️⃣ Define All Permissions in System (English => Arabic)
        // ======================================
        $permissions = [
            'manage_students'     => 'إدارة الطلاب',        // create / edit / delete students
            'manage_exams'     => 'إدارة الاختبارات',   // create / update exams
            'manage_questions' => 'إدارة الأسئلة',      // create / update questions
            'manage_answers'   => 'إدارة الإجابات',     // create answers
            'view_results'     => 'عرض النتائج',        // view exam results
            'manage_settings'  => 'إدارة الإعدادات',    // optional
            'view_reports'     => 'عرض التقارير',       // optional
        ];

        // Insert permissions with Arabic label
        foreach ($permissions as $name => $label) {
            Permission::updateOrCreate(
                ['name' => $name, 'guard_name' => 'user'],
                ['label' => $label] // you need to add 'label' column in permissions table
            );
        }

        // ======================================
        // 2️⃣ Define Roles & Attach Permissions
        // ======================================

        // --- Admin: FULL access ---
        $adminRole = Role::updateOrCreate(
            ['name' => 'Admin', 'guard_name' => 'user']
        );
        $adminRole->syncPermissions(array_keys($permissions)); // full access

        // --- Moderator: LIMITED access ---
        $moderatorRole = Role::updateOrCreate(
            ['name' => 'Moderator', 'guard_name' => 'user']
        );
        $moderatorRole->syncPermissions([
            'manage_users',
            'manage_exams',
            'manage_questions',
            'manage_answers',
            'view_results',
        ]);

        // ======================================
        // 3️⃣ Assign Roles to Users (example)
        // ======================================
        $userModel = 'Modules\UserModule\app\Http\Models\User';

        $assignments = [
            1 => 'Admin',
            2 => 'Moderator',
        ];

        foreach ($assignments as $userId => $roleName) {
            DB::table('model_has_roles')->updateOrInsert(
                [
                    'model_id'   => $userId,
                    'model_type' => $userModel,
                ],
                [
                    'role_id' => Role::where('name', $roleName)->value('id'),
                ]
            );
        }
    }
}
