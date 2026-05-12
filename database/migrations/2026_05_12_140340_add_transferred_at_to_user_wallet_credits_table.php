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
        Schema::table('user_wallet_credits', function (Blueprint $table) {
            $table->timestamp('transferred_at')->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('user_wallet_credits', function (Blueprint $table) {
            $table->dropColumn('transferred_at');
        });
    }
};
