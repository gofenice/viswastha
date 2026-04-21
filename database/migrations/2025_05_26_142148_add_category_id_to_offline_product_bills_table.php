<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToOfflineProductBillsTable extends Migration
{
    public function up()
    {
        Schema::table('offline_product_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('shop_id');

            $table->foreign('category_id')->references('id')->on('category_percentages')->onDelete('set null');
            
        });
    }

    public function down()
    {
        Schema::table('offline_product_bills', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
}
