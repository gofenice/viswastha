<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('admin_wallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable(); // Must be nullable for SET NULL to work
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->tinyInteger('type');
            $table->tinyInteger('status')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key constraints
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_wallet');
    }
};
