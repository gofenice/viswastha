<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * One row per user per day — records exactly HOW the daily binary
 * pair-match income was calculated.  Use this to audit or replay any day.
 *
 * UNIQUE (user_id, calc_date) prevents the cron from double-running.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binary_pair_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('calc_date');
            $table->enum('package_type', ['basic', 'premium']); // earner's package type

            // Raw counts for the day
            $table->integer('new_left')->default(0);            // activations in left leg today
            $table->integer('new_right')->default(0);           // activations in right leg today
            $table->integer('carry_in_left')->default(0);       // points rolled in from yesterday
            $table->integer('carry_in_right')->default(0);

            // After adding carry-ins
            $table->integer('total_left')->default(0);
            $table->integer('total_right')->default(0);

            // Matching result
            $table->integer('matched_pairs')->default(0);       // min(total_left, total_right)
            $table->integer('capped_pairs')->default(0);        // after daily cap (25 basic / 10 premium)
            $table->decimal('income', 12, 2)->default(0);       // capped_pairs × rate

            // Carry-forward & flush
            $table->integer('carry_out_left')->default(0);      // stronger-leg excess → next day
            $table->integer('carry_out_right')->default(0);
            $table->integer('flushed_left')->default(0);        // weaker-leg excess → discarded
            $table->integer('flushed_right')->default(0);

            $table->timestamps();

            $table->unique(['user_id', 'calc_date']);           // prevents double-run
            $table->index('calc_date');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('binary_pair_logs');
    }
};
