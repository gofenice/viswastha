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
        Schema::table('rank_incomes', function (Blueprint $table) {
            // Change the column type and add a foreign key
            $table->unsignedBigInteger('rank_id')->change();
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rank_incomes', function (Blueprint $table) {
            $table->dropForeign(['rank_id']);
            $table->string('rank_id')->change(); // Reverting back to string if needed
        });
    }
};
