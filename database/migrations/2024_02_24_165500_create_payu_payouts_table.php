<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use xGrz\PayU\Enums\PayoutStatus;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('payu_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->nullable();
            $table->unsignedTinyInteger('status')->default(PayoutStatus::INIT);
            $table->unsignedInteger('amount')->default(0);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('payu_payouts');
    }
};
