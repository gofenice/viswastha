<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseesTable extends Migration
{
    public function up()
    {
        Schema::create('franchisees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lbt_id'); // Local Body Type ID
            $table->unsignedBigInteger('lb_id');  // Local Body ID
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('status');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Optional: Add foreign key constraints
            $table->foreign('lbt_id')->references('id')->on('local_body_types')->onDelete('cascade');
            $table->foreign('lb_id')->references('id')->on('local_bodies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    } 

    public function down()
    {
        Schema::dropIfExists('franchisees');
    }
}

