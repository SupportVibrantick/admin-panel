<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('mode_name');
            $table->text('address')->nullable();
            $table->string('account_no');
            $table->string('bank_name');
            $table->string('ifsc_code');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_bank_details');
    }
};