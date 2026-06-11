<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fund_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('username')->nullable();
            $table->unsignedBigInteger('bank_detail_id');
            $table->string('payment_mode');
            $table->decimal('amount', 15, 2);
            $table->text('remark')->nullable();
            $table->string('mode_of_payment')->nullable(); // IMPS, NEFT, UPI
            $table->string('deposit_bank')->nullable();
            $table->string('transaction_no')->nullable();
            $table->date('deposit_date')->nullable();
            $table->string('hash_code_image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remark')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('mlm_users')->onDelete('cascade');
            $table->foreign('bank_detail_id')->references('id')->on('admin_bank_details')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fund_requests');
    }
};