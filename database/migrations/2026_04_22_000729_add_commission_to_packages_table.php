<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // BV points added to parent's leg per activation (used in BSV/PSV display and pair matching)
            $table->decimal('binary_commission', 10, 2)->default(0)->after('amount');
            // Fixed rupee amount credited to direct sponsor on activation
            $table->decimal('sponsor_commission', 10, 2)->default(0)->after('binary_commission');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['binary_commission', 'sponsor_commission']);
        });
    }
};
