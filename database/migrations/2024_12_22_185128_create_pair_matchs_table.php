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
        Schema::create('pair_matchs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('pair_user1_id');
            $table->unsignedBigInteger('pair_user2_id');
            $table->unsignedBigInteger('package_id');
            $table->decimal('pair_match_income', 10, 2);
            $table->date('pair_match_income_date');
            $table->boolean('status')->default(1);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

             // Add foreign keys if needed
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pair_user1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pair_user2_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pair_matchs');
    }
};
