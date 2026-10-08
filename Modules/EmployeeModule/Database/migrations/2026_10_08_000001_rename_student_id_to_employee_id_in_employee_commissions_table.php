<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The column always held the employee id; only its name was wrong.
        Schema::table('employee_commissions', function (Blueprint $table) {
            $table->renameColumn('student_id', 'employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_commissions', function (Blueprint $table) {
            $table->renameColumn('employee_id', 'student_id');
        });
    }
};
