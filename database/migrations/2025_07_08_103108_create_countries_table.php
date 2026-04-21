<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Country name (e.g., India)
            $table->string('iso2', 2)->unique(); // ISO 3166-1 alpha-2 (e.g., IN)
            $table->string('iso3', 3)->unique(); // ISO 3166-1 alpha-3 (e.g., IND)
            $table->string('phone_code')->nullable(); // e.g., +91
            $table->string('currency')->nullable(); // e.g., INR
            $table->string('currency_symbol')->nullable(); // e.g., ₹
            $table->string('region')->nullable(); // e.g., Asia
            $table->string('subregion')->nullable(); // e.g., Southern Asia
            $table->boolean('status')->default(true); // Active or not
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}

