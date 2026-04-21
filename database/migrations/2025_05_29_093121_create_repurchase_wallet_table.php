<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repurchase_wallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('product_ordered_user_id')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('order_id');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_redeemed')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_ordered_user_id')->references('id')->on('users')->onDelete('set null');
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repurchase_wallet');
    }
};
