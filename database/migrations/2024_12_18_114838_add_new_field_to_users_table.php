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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_image')->nullable()->after('password'); 
            $table->unsignedBigInteger('package_id')->nullable()->after('user_image');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
            $table->string('user_rank', 50)->nullable()->after('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['user_image', 'package_id', 'user_rank']);
        });
    }
};
