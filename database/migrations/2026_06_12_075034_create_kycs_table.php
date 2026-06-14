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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('mlm_users')->cascadeOnDelete();

            $table->string('pan_number', 10);
            $table->string('aadhaar_number', 12);

            $table->string('pan_image');
            $table->string('aadhaar_front_image');
            $table->string('aadhaar_back_image');

            $table->string('bank_document_image');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->text('reject_reason')->nullable();

            $table->timestamps();

            $table->unique('user_id');
            $table->unique('pan_number');
            $table->unique('aadhaar_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
