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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->string('title');
            $table->text('details')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['new', 'in_progress', 'completed', 'late'])->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tasks');
    }
};
