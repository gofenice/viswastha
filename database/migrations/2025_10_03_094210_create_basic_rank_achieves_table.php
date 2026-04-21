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
        Schema::create('basic_rank_achieves', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('basic_rank_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sponsor_id')->nullable();

            $table->tinyInteger('status')->default(0); // 0 = pending, 1 = achieved, etc.
            $table->tinyInteger('rank_status')->default(1);

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys (optional but recommended)
            $table->foreign('basic_rank_id')->references('id')->on('basic_ranks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sponsor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_rank_achieves');
    }
};
