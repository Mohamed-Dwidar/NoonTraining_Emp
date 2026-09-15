<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->default('custom'); // 'custom' or 'violation'
            $table->bigInteger('employee_id');
            $table->string('month', 7);           // e.g. 2026-06
            $table->bigInteger('violation_id')->nullable();
            $table->tinyInteger('violation_repeat_number')->nullable();
            $table->decimal('amount', 10, 2);   // e.g. 1500.00
            $table->string('reason');            // e.g. 'performance', 'attendance', etc.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deductions');
    }
};
