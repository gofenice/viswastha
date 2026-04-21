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
        Schema::create('pair_match_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pair_match_id'); // Foreign key referencing match_income table
            $table->unsignedBigInteger('user_id'); // Foreign key referencing users table
            $table->decimal('income', 10, 2); // Income field with precision
            $table->unsignedBigInteger('package_id')->nullable(); // Foreign key referencing packages table
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status column
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('pair_match_id')->references('id')->on('pair_matchs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pair_match_incomes');
    }
};
