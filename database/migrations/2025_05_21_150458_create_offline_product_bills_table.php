<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineProductBillsTable extends Migration
{
    public function up()
    {
        Schema::create('offline_product_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->date('purchase_date');
            $table->integer('product_count');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->string('status');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('offline_product_bills');
    }
}
