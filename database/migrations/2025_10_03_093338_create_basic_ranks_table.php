<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basic_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., No Rank, 1 Star, 2 Star ...
            $table->integer('level')->default(0); // 0 = No Rank, 1 = 1 Star ...
            $table->tinyInteger('status')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        DB::table('basic_ranks')->insert([
            ['name' => 'No Rank', 'level' => 0],
            ['name' => '1 Star', 'level' => 1],
            ['name' => '2 Star', 'level' => 2],
            ['name' => '3 Star', 'level' => 3],
            ['name' => '4 Star', 'level' => 4],
            ['name' => '5 Star', 'level' => 5],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_ranks');
    }
};
