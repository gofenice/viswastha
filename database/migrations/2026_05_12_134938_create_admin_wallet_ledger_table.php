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
        Schema::create('admin_wallet_ledger', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_type');          // privilege|board|executive|royalty
            $table->decimal('amount', 15, 2);       // amount credited to admin
            $table->foreignId('distribution_id')->constrained('wallet_distributions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_wallet_ledger');
    }
};
