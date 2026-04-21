<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalBodyFieldsToOfflineProductBillsTable extends Migration
{
    public function up()
    {
        Schema::table('offline_product_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('lbt_id')->nullable()->after('shop_id');
            $table->unsignedBigInteger('lb_id')->nullable()->after('lbt_id');

            // (Optional) Add foreign key constraints if needed
            $table->foreign('lbt_id')->references('id')->on('local_body_types')->onDelete('set null');
            $table->foreign('lb_id')->references('id')->on('local_bodies')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('offline_product_bills', function (Blueprint $table) {
            $table->dropColumn(['lbt_id', 'lb_id']);
        });
    }
}
