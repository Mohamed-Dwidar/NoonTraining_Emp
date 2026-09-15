<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('attendance_rate', 5, 2)->default(0)->after('days_absent');
            $table->string('status')->default('منتظم')->after('attendance_rate');
            $table->unique(['employee_id', 'month'], 'attendances_employee_month_unique');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_employee_month_unique');
            $table->dropColumn(['attendance_rate', 'status']);
        });
    }
};
