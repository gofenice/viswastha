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
            $table->unsignedSmallInteger('prime_carry_out_left')->default(0)->after('flushed_right');
            $table->unsignedSmallInteger('prime_carry_out_right')->default(0)->after('prime_carry_out_left');
        });
    }

    public function down(): void
    {
        Schema::table('binary_pair_logs', function (Blueprint $table) {
            $table->dropColumn(['prime_carry_out_left', 'prime_carry_out_right']);
        });
    }
};
