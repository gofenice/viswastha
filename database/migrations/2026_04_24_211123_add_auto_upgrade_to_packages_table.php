<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->unsignedInteger('auto_upgrade_count')->nullable()->after('sponsor_eligible_package_ids');
            $table->unsignedBigInteger('auto_upgrade_to_package_id')->nullable()->after('auto_upgrade_count');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['auto_upgrade_count', 'auto_upgrade_to_package_id']);
        });
    }
};
