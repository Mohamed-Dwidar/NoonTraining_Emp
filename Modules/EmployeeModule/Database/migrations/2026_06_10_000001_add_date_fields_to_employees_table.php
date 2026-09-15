<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up() {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('hired_at')->nullable()->after('status');
            $table->date('contract_ends_at')->nullable()->after('hired_at');
            $table->date('terminated_at')->nullable()->after('contract_ends_at');
        });
    }

    public function down() {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['hired_at', 'contract_ends_at', 'terminated_at']);
        });
    }
};
