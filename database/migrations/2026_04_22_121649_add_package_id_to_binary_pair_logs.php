<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable()->after('user_id');
            $table->string('package_type', 50)->default('basic')->change(); // widen from enum
            $table->index(['user_id', 'calc_date', 'package_id'], 'bpl_user_date_pkg');
        });
    }

    public function down(): void
    {
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->dropIndex('bpl_user_date_pkg');
            $table->dropColumn('package_id');
        });
    }
};
