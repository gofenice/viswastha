<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->dropUnique('binary_pair_logs_user_id_calc_date_unique');
            $table->unique(['user_id', 'calc_date', 'package_id'], 'bpl_user_date_package_unique');
        });
    }

    public function down(): void
    {
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->dropUnique('bpl_user_date_package_unique');
            $table->unique(['user_id', 'calc_date'], 'binary_pair_logs_user_id_calc_date_unique');
        });
    }
};
