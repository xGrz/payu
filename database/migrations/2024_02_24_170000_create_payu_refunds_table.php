<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use xGrz\PayU\Enums\RefundStatus;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('payu_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('transaction_id')->references('id')->on('payu_transactions');
            $table->unsignedTinyInteger('status')->default(RefundStatus::INITIALIZED);
            $table->text('description');
            $table->text('bank_description')->nullable();
            $table->unsignedInteger('amount')->default(0);
            $table->string('currency_code')->nullable();
            $table->string('ext_refund_id')->nullable();
            $table->string('refund_id')->nullable();
            $table->string('error')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('payu_refunds');
    }
};
