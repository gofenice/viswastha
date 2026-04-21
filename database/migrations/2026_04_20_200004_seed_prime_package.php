<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only insert if no prime package exists yet
        $exists = DB::table('packages')->where('package_code', 'prime_package')->exists();
        if (!$exists) {
            DB::table('packages')->insert([
                'name'         => 'Prime Package',
                'amount'       => 10000,   // adjust via admin if needed
                'package_code' => 'prime_package',
                'package_cat'  => 0,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('packages')->where('package_code', 'prime_package')->delete();
    }
};
