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
        Schema::create('grivances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('mlm_users');

            $table->string('ticket_no')->unique();

            $table->string('subject');

            $table->enum('category', [
                'dispatch',
                'e-wallet',
                'software-issue',
                'kyc',
                'TDS-and-gst',
                'direct-seller',
                'product-and-quality',
                'other'
            ]);

            $table->enum('priority', [
                'low',
                'medium',
                'high'
            ])->default('medium');

            $table->enum('status', [
                'open',
                'in_progress',
                'closed'
            ])->default('open');

            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grivances');
    }
};
