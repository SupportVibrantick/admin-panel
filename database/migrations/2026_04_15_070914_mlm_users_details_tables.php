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
        Schema::create('mlm_users_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('mlm_users')
                ->onDelete('cascade');

            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();

            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('pincode', 10)->nullable();

            $table->string('nominee_name')->nullable();
            $table->string('nominee_relation')->nullable(); 

            $table->string('profile_image')->nullable();

            $table->timestamps();
            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_users_details');
    }
};
