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
            $table->unsignedBigInteger('assigned_board_member_id')->nullable()->after('sponsor_id');
            // Assuming 'assigned_board_member_id' references 'user_id' in 'board_members' which references 'id' in 'users'.
            // Or 'assigned_board_member_id' references 'id' in 'users' where that user is a board member?
            // The previous code logic: "Find the last registered user who has a sponsor from the board members list"
            // "User::whereIn('sponsor_id', $boardMembers)" implies the sponsor IS the board member.
            // The request "create a new additional field in user table and add sponsor id also that field"
            // So 'assigned_board_member_id' basically stores WHO the board member was that acted as the sponsor (or cycled).
            // So it references a User.
            $table->foreign('assigned_board_member_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
