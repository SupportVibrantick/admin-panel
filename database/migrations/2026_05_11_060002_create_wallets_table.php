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
       Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Wallet Display Name
            $table->string('code')->unique(); // Internal Code (COMMISSION, PURCHASE, REWARD)
            $table->string('currency_code')->default('INR');
            $table->enum('eligibility', ['ALL', 'SPONSORED_ONLY', 'ACTIVE_MEMBERS'])->default('ALL');
            $table->enum('type', ['CREDIT', 'DEBIT', 'BOTH'])->default('BOTH');
            $table->boolean('is_active')->default(true);
            $table->decimal('min_balance', 12, 2)->default(0);
            $table->decimal('max_balance', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
