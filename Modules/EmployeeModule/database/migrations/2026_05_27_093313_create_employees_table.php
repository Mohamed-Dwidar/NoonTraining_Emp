<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('branch_id')->default(0);
            $table->bigInteger('department_id')->default(0);
            $table->string('job');
            $table->decimal('basic_salary', 10, 2);
            $table->tinyInteger('monthly_working_days');
            $table->tinyInteger('daily_working_hours');
            $table->decimal('stu_commission', 10, 2);   //Student Commission for training employees
            $table->enum('status', [
                'active',
                'suspended',
                'on_leave',
                'resigned',
                'terminated'
            ])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('employees');
    }
};
