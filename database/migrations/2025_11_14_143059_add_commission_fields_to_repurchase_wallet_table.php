<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repurchase_wallet', function (Blueprint $table) {

            // Add commission amount
            $table->decimal('commission_amount', 10, 2)
                ->nullable()
                ->after('percentage');

            // Add commission percentage
            $table->decimal('commission_percentage', 5, 2)
                ->nullable()
                ->after('commission_amount');

            // Add shop id
            $table->unsignedBigInteger('shop_id')
                ->nullable()
                ->after('commission_percentage');

            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('repurchase_wallet', function (Blueprint $table) {
            $table->dropColumn([
                'commission_amount',
                'commission_percentage',
                'shop_id'
            ]);
        });
    }
};
