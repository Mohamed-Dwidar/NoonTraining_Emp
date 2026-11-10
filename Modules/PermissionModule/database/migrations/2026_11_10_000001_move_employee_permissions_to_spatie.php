<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Employee permissions move to spatie (guard `employee`), like Acc_NoonTraining.
 *
 * - Creates can_edit / can_delete / can_view_work_regulations.
 * - Existing employees keep today's behaviour: everyone gets can_edit + can_delete;
 *   can_view_work_regulations only where employees.can_view_work_regulations = 1.
 * - Drops employees.can_view_work_regulations (down() restores it from the permission).
 *
 * Uses the query builder (not models) so it stays valid if the models change later.
 */
return new class extends Migration {
    private const GUARD = 'employee';
    private const USER_MODEL = 'Modules\\UserModule\\App\\Http\\Models\\User';
    private const EMPLOYEE_MODEL = 'Modules\\EmployeeModule\\App\\Http\\Models\\Employee';

    private const PERMISSIONS = [
        'can_edit'                  => 'امكانية تعديل الطلاب',
        'can_delete'                => 'امكانية حذف الطلاب',
        'can_view_work_regulations' => 'عرض لائحة العمل',
    ];

    public function up(): void {
        $now = now();
        $ids = [];
        foreach (self::PERMISSIONS as $name => $label) {
            $id = DB::table('permissions')->where('name', $name)->where('guard_name', self::GUARD)->value('id');
            if (!$id) {
                $id = DB::table('permissions')->insertGetId([
                    'name' => $name, 'label' => $label, 'guard_name' => self::GUARD,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
            $ids[$name] = $id;
        }

        // users may not exist yet on a fresh install run module by module (UserModule migrates after PermissionModule)
        if (Schema::hasTable('users')) {
            $employeeUsers = DB::table('users')
                ->where('userable_type', self::EMPLOYEE_MODEL)
                ->pluck('id', 'userable_id'); // employee_id => user_id

            $canViewRegs = Schema::hasColumn('employees', 'can_view_work_regulations')
                ? DB::table('employees')->where('can_view_work_regulations', true)->pluck('id')->flip()
                : collect();

            foreach ($employeeUsers as $employeeId => $userId) {
                $grant = ['can_edit', 'can_delete'];
                if ($canViewRegs->has($employeeId)) {
                    $grant[] = 'can_view_work_regulations';
                }
                foreach ($grant as $name) {
                    $this->grant($ids[$name], $userId, $now);
                }
            }
        }

        if (Schema::hasColumn('employees', 'can_view_work_regulations')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('can_view_work_regulations');
            });
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void {
        if (!Schema::hasColumn('employees', 'can_view_work_regulations')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->boolean('can_view_work_regulations')->default(false)->after('terminated_at');
            });
        }

        $ids = DB::table('permissions')->where('guard_name', self::GUARD)
            ->whereIn('name', array_keys(self::PERMISSIONS))->pluck('id', 'name');

        if (isset($ids['can_view_work_regulations']) && Schema::hasTable('users')) {
            $employeeIds = DB::table('model_has_permissions as mp')
                ->join('users as u', 'u.id', '=', 'mp.model_id')
                ->where('mp.model_type', self::USER_MODEL)
                ->where('mp.permission_id', $ids['can_view_work_regulations'])
                ->where('u.userable_type', self::EMPLOYEE_MODEL)
                ->pluck('u.userable_id');
            DB::table('employees')->whereIn('id', $employeeIds)->update(['can_view_work_regulations' => true]);
        }

        DB::table('model_has_permissions')->whereIn('permission_id', $ids->values())->delete();
        DB::table('role_has_permissions')->whereIn('permission_id', $ids->values())->delete();
        DB::table('permissions')->whereIn('id', $ids->values())->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function grant(int $permissionId, int $userId, $now): void {
        $exists = DB::table('model_has_permissions')
            ->where(['permission_id' => $permissionId, 'model_id' => $userId, 'model_type' => self::USER_MODEL])
            ->exists();
        if (!$exists) {
            DB::table('model_has_permissions')->insert([
                'permission_id' => $permissionId, 'model_id' => $userId, 'model_type' => self::USER_MODEL,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
};
