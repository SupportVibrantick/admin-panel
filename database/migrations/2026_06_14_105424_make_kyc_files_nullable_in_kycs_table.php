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
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('pan_image')->nullable()->change();
            $table->string('aadhaar_front_image')->nullable()->change();
            $table->string('aadhaar_back_image')->nullable()->change();
            $table->string('bank_document_image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('pan_image')->nullable(false)->change();
            $table->string('aadhaar_front_image')->nullable(false)->change();
            $table->string('aadhaar_back_image')->nullable(false)->change();
        });
    }
};
