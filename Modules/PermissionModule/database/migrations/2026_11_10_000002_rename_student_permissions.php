<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Rename can_edit → can_edit_students and can_delete → can_delete_students (guard `employee`).
 * Renaming in place keeps every employee's assignments (model_has_permissions uses the id).
 */
return new class extends Migration {
    private const GUARD = 'employee';

    private const RENAMES = [
        'can_edit'   => 'can_edit_students',
        'can_delete' => 'can_delete_students',
    ];

    public function up(): void {
        foreach (self::RENAMES as $old => $new) {
            $this->rename($old, $new);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void {
        foreach (self::RENAMES as $old => $new) {
            $this->rename($new, $old);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function rename(string $from, string $to): void {
        // Skip if the target already exists (e.g. created by the seeder) to avoid duplicates.
        $exists = DB::table('permissions')->where('guard_name', self::GUARD)->where('name', $to)->exists();
        if (!$exists) {
            DB::table('permissions')->where('guard_name', self::GUARD)->where('name', $from)
                ->update(['name' => $to, 'updated_at' => now()]);
        }
    }
};
