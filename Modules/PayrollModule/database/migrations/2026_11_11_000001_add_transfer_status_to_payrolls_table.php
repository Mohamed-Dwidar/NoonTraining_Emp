<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Salary transfer status per payroll (employee + month).
 * Not touched by PayrollRepository::recalculateForEmployee (it only updates the salary figures).
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->string('transfer_status', 20)->default('pending')->after('total_salary'); // pending | sent
            $table->date('transfer_date')->nullable()->after('transfer_status');               // تاريخ الحوالة
        });
    }

    public function down(): void {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['transfer_status', 'transfer_date']);
        });
    }
};
