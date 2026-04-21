<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLbtAndLbToShopsTable extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedBigInteger('lbt_id')->nullable()->after('district_id');
            $table->unsignedBigInteger('lb_id')->nullable()->after('lbt_id');

            // Optional: Add foreign keys if you have corresponding tables
            $table->foreign('lbt_id')->references('id')->on('local_body_types')->onDelete('set null');
            $table->foreign('lb_id')->references('id')->on('local_bodies')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Drop foreign keys first if they were added
            $table->dropForeign(['lbt_id']);
            $table->dropForeign(['lb_id']);

            $table->dropColumn(['lbt_id', 'lb_id']);
        });
    }
}
