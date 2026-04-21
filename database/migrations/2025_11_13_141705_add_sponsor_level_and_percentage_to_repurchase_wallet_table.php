<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repurchase_wallet', function (Blueprint $table) {

            // Add level of sponsor distribution
            $table->integer('sponsor_level')->nullable()->after('amount_type');

            // Add percentage applied for that sponsor
            $table->decimal('percentage', 5, 2)->nullable()->after('sponsor_level');
        });
    }

    public function down(): void
    {
        Schema::table('repurchase_wallet', function (Blueprint $table) {
            $table->dropColumn(['sponsor_level', 'percentage']);
        });
    }
};
