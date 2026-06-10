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
        Schema::table('mlm_users', function (Blueprint $table) {
            $table->tinyInteger('commission_percentage')
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mlm_users', function (Blueprint $table) {
            $table->tinyInteger('commission_percentage')
                ->default(10)
                ->nullable(false)
                ->change();
        });
    }
};
