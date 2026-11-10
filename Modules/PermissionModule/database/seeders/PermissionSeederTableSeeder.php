<?php

namespace Modules\PermissionModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Employee-portal permissions (spatie, guard `employee`). Safe to re-run (firstOrCreate).
 * Add a new permission here + a check (route `permission:<name>` / @can) — the employee
 * create/edit form lists every `employee`-guard permission automatically, with its label.
 */
class PermissionSeederTableSeeder extends Seeder
{
    public const GUARD = 'employee';

    public const PERMISSIONS = [
        'can_edit_students'         => 'امكانية تعديل الطلاب',
        'can_delete_students'       => 'امكانية حذف الطلاب',
        'can_view_work_regulations' => 'عرض لائحة العمل',
    ];

    public function run()
    {
        foreach (self::PERMISSIONS as $name => $label) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => self::GUARD],
                ['label' => $label]
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
