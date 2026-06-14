<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Makes sender_id nullable so admin replies (not in mlm_users table)
     * can be stored. A NULL sender_id means the message was sent by an admin.
     */
    public function up(): void
    {
        Schema::table('grievance_massages', function (Blueprint $table) {
            // Drop the existing FK constraint first
            $table->dropForeign(['sender_id']);

            // Modify to nullable and re-add FK without restrict
            $table->unsignedBigInteger('sender_id')->nullable()->change();

            $table->foreign('sender_id')
                ->references('id')
                ->on('mlm_users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grievance_massages', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);

            $table->unsignedBigInteger('sender_id')->nullable(false)->change();

            $table->foreign('sender_id')
                ->references('id')
                ->on('mlm_users');
        });
    }
};
