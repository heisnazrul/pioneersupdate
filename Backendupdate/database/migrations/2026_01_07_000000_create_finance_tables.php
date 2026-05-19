<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->char('base_currency', 3);
            $table->char('target_currency', 3);
            $table->decimal('rate', 16, 8);
            $table->timestamps();
            $table->unique(['base_currency', 'target_currency'], 'uq_exchange_rates');
            $table->index('base_currency');
        });

        Schema::create('conversion_fees', function (Blueprint $table) {
            $table->id();
            $table->char('base_currency', 3);
            $table->char('target_currency', 3);
            $table->decimal('fee', 5, 2)->default(0.00);
            $table->timestamps();
            $table->unique(['base_currency', 'target_currency'], 'uq_conversion_fees');
            $table->index('base_currency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversion_fees');
        Schema::dropIfExists('exchange_rates');
    }
};
