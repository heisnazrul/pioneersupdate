<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversion_fees', function (Blueprint $table): void {
            $table->id();
            $table->char('base_currency', 3);
            $table->char('target_currency', 3);
            $table->decimal('fee', 5, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['base_currency', 'target_currency'], 'uq_conversion_fees_base_target');
            $table->index('base_currency', 'idx_conversion_fees_base');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversion_fees');
    }
};
