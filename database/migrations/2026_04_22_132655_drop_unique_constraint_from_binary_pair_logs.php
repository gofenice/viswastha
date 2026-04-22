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
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->dropUnique('bpl_user_date_package_unique');
        });
    }

    public function down(): void
    {
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->unique(['user_id', 'calc_date', 'package_id'], 'bpl_user_date_package_unique');
        });
    }
};
