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
        Schema::table('product_delivery_details', function (Blueprint $table) {
            $table->enum('delivery_type', ['courier', 'office_pickup'])->default('courier')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_delivery_details', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
        });
    }
};
