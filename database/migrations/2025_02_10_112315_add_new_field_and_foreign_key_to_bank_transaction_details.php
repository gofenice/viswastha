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
        Schema::table('bank_transaction_details', function (Blueprint $table) {

            $table->string('transaction_id')->nullable()->after('date_of_send');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transaction_details', function (Blueprint $table) {

            $table->dropColumn('transaction_id');

            $table->dropForeign(['user_id']);
        });
    }
};
