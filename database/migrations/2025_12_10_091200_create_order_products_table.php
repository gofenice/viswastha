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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('online_order_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price_tax_excl', 10, 2)->default(0);
            $table->decimal('total_price_tax_excl', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->unsignedBigInteger('franchise_code')->nullable();
            $table->boolean('is_approve')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('online_order_id')->references('id')->on('online_orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('franchise_code')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
