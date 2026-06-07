<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('violation_repeats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('violation_id');
            $table->tinyInteger('repeat_number');
            $table->decimal('deduction_amount', 10, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('violation_repeats');
    }
};
