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
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('category')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->unsignedBigInteger('franchisee_code')->nullable();
            $table->boolean('is_approve')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('franchisee_code')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_orders');
    }
};
