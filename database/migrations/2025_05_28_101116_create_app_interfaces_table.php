<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppInterfacesTable extends Migration
{
    public function up()
    {
        Schema::create('app_interfaces', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('text')->nullable();
            $table->boolean('status')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_interfaces');
    }
}

