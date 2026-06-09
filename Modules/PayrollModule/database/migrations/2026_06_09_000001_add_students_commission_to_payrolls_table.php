<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up() {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('students_commission', 10, 2)->default(0)->after('bonuses');
        });
    }

    public function down() {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('students_commission');
        });
    }
};
