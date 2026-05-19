<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->string('use_case', 100)->nullable();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->timestamps();

            $table->index('use_case', 'idx_galleries_use_case');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
