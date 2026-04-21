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
        Schema::table('users', function (Blueprint $table) {
            // Remove the user_rank field
            $table->dropColumn('user_rank');

            $table->unsignedBigInteger('rank_id')->nullable()->after('package_id');
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key and rank_id column
            $table->dropForeign(['rank_id']);
            $table->dropColumn('rank_id');

            // Re-add the old user_rank field
            $table->string('user_rank', 50)->nullable()->after('package_id');
        });
    }
};
