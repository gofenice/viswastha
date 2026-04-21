<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('repurchase_wallet', function (Blueprint $table) {
            $table->string('amount_type')->nullable()->after('amount'); // Or use enum if fixed types
        });
    }

    public function down(): void
    {
        Schema::table('repurchase_wallet', function (Blueprint $table) {
            $table->dropColumn('amount_type');
        });
    }
};

