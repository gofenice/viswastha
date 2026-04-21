<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalBodiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('local_bodies', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('country_id');
            $table->unsignedInteger('state_id');
            $table->unsignedInteger('district_id');
            $table->unsignedBigInteger('lbt_id');

            // Other fields
            $table->string('name');
            $table->string('lb_code', 50)->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('state_id')->on('states')->onDelete('cascade');
            $table->foreign('district_id')->references('district_id')->on('districts')->onDelete('cascade');
            $table->foreign('lbt_id')->references('id')->on('local_body_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_bodies');
    }
}
