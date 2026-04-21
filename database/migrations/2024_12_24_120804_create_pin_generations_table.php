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
        Schema::create('pin_generations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('unique_id')->unique();
            $table->unsignedBigInteger('package_id');
            $table->string('transfer_to')->nullable();
            $table->string('password');
            $table->decimal('pin_amount', 15, 2);
            $table->unsignedTinyInteger('used')->default(0)->comment('0: default, 1: currently logged person used, 2: transferred person used');
            $table->enum('status', ['pending', 'transferred', 'redeemed','rejected'])->default('pending');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
                        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pin_generations');
    }
};
