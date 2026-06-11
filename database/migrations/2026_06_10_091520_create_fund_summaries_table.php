<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fund_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('username')->nullable();
            $table->date('transaction_date');
            $table->string('type'); // ADMIN CREDIT, ADMIN DEBIT, Admin Transfer, etc.
            $table->string('particular');
            $table->text('remark')->nullable();
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('mlm_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fund_summaries');
    }
};