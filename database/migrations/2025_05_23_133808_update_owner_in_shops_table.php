<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOwnerInShopsTable extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('owner_name');
            $table->unsignedBigInteger('owner_id')->nullable()->after('name');

            // If you want to add a foreign key
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('owner_id');
            $table->string('owner_name')->nullable()->after('name');
        });
    }
}

