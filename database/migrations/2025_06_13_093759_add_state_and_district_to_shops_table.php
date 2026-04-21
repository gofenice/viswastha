<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateAndDistrictToShopsTable extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedInteger('state_id')->after('id')->nullable();
            $table->unsignedInteger('district_id')->after('state_id')->nullable();

            // Optional: Add foreign keys if related tables exist
            $table->foreign('state_id')->references('state_id')->on('states')->onDelete('cascade');
            $table->foreign('district_id')->references('district_id')->on('districts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['state_id', 'district_id']);
        });
    }
}
