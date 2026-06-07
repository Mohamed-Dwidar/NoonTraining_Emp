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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->string('name');
            $table->string('mobile');
            $table->string('course_name');
            $table->integer('total_amount');
            $table->integer('paid_amount');
            $table->string('payment_method');
            $table->string('payment_date');
            $table->string('previous_student_of')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('students');
    }
};
