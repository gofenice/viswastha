<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('msg_from_id');
            $table->unsignedBigInteger('msg_to_id');
            $table->text('message');
            $table->boolean('msg_is_read')->default(false);
            $table->boolean('msg_edited')->default(false);
            $table->string('msg_status')->default('active');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('msg_from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('msg_to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
