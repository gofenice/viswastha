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
        Schema::table('child_mother_payments', function (Blueprint $table) {
            $table->tinyInteger('type')->after('amount')->nullable()->comment('1-Level, 2-Referral, 3-Rank, 4-Royalty');
        });
    }

    /**
     * Reverse the migrations.
     */
    
    public function down(): void
    {
        Schema::table('child_mother_payments', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
