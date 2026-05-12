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
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('privilege_wallet_income', 10, 2)->default(0)->after('daily_pair_cap');
            $table->decimal('board_wallet_income',     10, 2)->default(0)->after('privilege_wallet_income');
            $table->decimal('executive_wallet_income', 10, 2)->default(0)->after('board_wallet_income');
            $table->decimal('royalty_wallet_income',   10, 2)->default(0)->after('executive_wallet_income');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'privilege_wallet_income',
                'board_wallet_income',
                'executive_wallet_income',
                'royalty_wallet_income',
            ]);
        });
    }
};
