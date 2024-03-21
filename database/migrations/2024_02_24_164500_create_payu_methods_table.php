<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('payu_methods', function (Blueprint $table) {
            $table->string('code')->unique()->primary();
            $table->string('name');
            $table->string('image')->nullable();
            $table->boolean('available')->default(false);
            $table->unsignedInteger('min_amount')->default(0);
            $table->unsignedInteger('max_amount')->default(99999999);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payu_methods');
    }
};
