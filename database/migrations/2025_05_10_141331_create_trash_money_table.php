<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trash_money', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('trashed_by')->nullable()->comment('Admin/User who trashed');
            $table->timestamps();

            // Foreign keys (optional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('trashed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash_money');
    }
};

