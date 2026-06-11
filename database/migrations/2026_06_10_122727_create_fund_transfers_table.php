<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->string('sender_username');
            $table->string('receiver_username');
            $table->decimal('amount', 15, 2);
            $table->text('remark')->nullable();
            $table->string('transaction_password');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('mlm_users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('mlm_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fund_transfers');
    }
};