<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binary_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->decimal('balance', 12, 2)->default(0);          // current withdrawable balance
            $table->decimal('total_earned', 12, 2)->default(0);     // all-time earnings
            $table->decimal('total_withdrawn', 12, 2)->default(0);  // all-time withdrawals
            $table->integer('carry_forward_left')->default(0);      // unmatched left-leg points rolled over
            $table->integer('carry_forward_right')->default(0);     // unmatched right-leg points rolled over
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('binary_wallets');
    }
};
