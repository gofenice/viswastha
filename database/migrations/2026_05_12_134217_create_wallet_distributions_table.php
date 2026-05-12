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
        Schema::create('wallet_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_type');
            $table->decimal('pool_amount', 15, 2);      // undistributed entries + admin balance brought forward
            $table->unsignedInteger('user_count');
            $table->decimal('per_user_amount', 15, 2);
            $table->decimal('total_distributed', 15, 2);
            $table->decimal('remainder', 15, 2);         // kept in admin wallet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_distributions');
    }
};
