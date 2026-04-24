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
        Schema::table('user_packages', function (Blueprint $table) {
            $table->string('deactivation_reason')->nullable()->after('status');
            $table->unsignedBigInteger('upgraded_from_package_id')->nullable()->after('deactivation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('user_packages', function (Blueprint $table) {
            $table->dropColumn(['deactivation_reason', 'upgraded_from_package_id']);
        });
    }
};
