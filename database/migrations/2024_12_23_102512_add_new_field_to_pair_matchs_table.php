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
        Schema::table('pair_matchs', function (Blueprint $table) {
          
            $table->decimal('bonus_amount', 10, 2)->after('pair_match_income')->nullable()->default(0.00);
            $table->unsignedBigInteger('sponsor_id')->after('bonus_amount');

            $table->foreign('sponsor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pair_matchs', function (Blueprint $table) {
            $table->dropColumn('bonus_amount');
            $table->dropColumn('sponsor_id');
        });
    }
};
