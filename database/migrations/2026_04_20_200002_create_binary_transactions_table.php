<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Unified audit ledger for ALL binary income events.
 * Every credit and debit is one row here — query this to answer
 * "how did user X earn Y" or "total withdrawn this month".
 *
 * types:
 *   binary_pair     — daily pair-match income (cron job)
 *   binary_sponsor  — direct sponsor income on Basic/Premium activation
 *   prime_sponsor   — ₹500 sponsor income when a Prime user activates under you
 *   withdrawal      — approved withdrawal (negative amount)
 *   admin_credit    — manual admin adjustment
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binary_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', [
                'binary_pair',
                'binary_sponsor',
                'prime_sponsor',
                'withdrawal',
                'admin_credit',
            ]);
            $table->decimal('amount', 12, 2);           // positive = credit, negative = debit
            $table->string('description')->nullable();  // human-readable label
            $table->unsignedBigInteger('from_user_id')->nullable(); // who triggered this income
            $table->unsignedBigInteger('package_id')->nullable();   // which package triggered it
            $table->date('calc_date')->nullable();                  // for pair income: the calculation date
            $table->json('meta')->nullable();                       // extra context (pairs count, carry-forward, etc.)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index(['user_id', 'type']);
            $table->index('calc_date');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('binary_transactions');
    }
};
