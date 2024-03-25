<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use xGrz\PayU\Enums\PaymentStatus;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('payu_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('payu_order_id', 50);
            $table->text('link');
            $table->unsignedInteger('amount')->nullable();
            $table->unsignedInteger('refunded')->nullable();
            $table->json('payload');
            $table->unsignedTinyInteger('status')->default(PaymentStatus::INITIALIZED);
            $table->string('method_id')->nullable();
            $table->nullableMorphs('payuable');
            $table->timestamps();
        });

        Schema::table('payu_transactions', function (Blueprint $table) {
            $table->foreign('method_id')->references('code')->on('payu_methods');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payu_transactions');
    }
};
