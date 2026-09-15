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
        // course_commision_type stored a Setting id (string); commission_id now
        // references commissions.id instead, so the old values no longer apply.
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('course_commision_type');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_id')->nullable()->after('previous_student_of');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('commission_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('course_commision_type')->nullable()->after('previous_student_of');
        });
    }
};
