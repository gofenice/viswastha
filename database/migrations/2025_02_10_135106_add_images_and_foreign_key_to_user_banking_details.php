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
        Schema::table('user_banking_details', function (Blueprint $table) {

            $table->string('bank_passbook_image')->nullable()->after('status');
            $table->string('pancard_image')->nullable()->after('bank_passbook_image');

            
            // Setting foreign key constraint on user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_banking_details', function (Blueprint $table) {

            $table->dropColumn(['bank_passbook_image', 'pancard_image']);

            // Dropping foreign key constraint
            $table->dropForeign(['user_id']);
        });
    }
};
