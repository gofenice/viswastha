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
            // Drop unique constraints by their generated names
            $table->dropUnique(['phone_no']);
            $table->dropUnique(['pan_card_no']);

            // Add new fields
            $table->string('gender')->after('pan_card_no')->nullable();
            $table->string('is_pair_matched')->after('role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add unique constraints
            $table->unique('phone_no');
            $table->unique('pan_card_no');

            // Drop new fields
            $table->dropColumn('gender'); 
            $table->dropColumn('is_pair_matched'); 
        });
    }
};
