<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('city');
            $table->string('ar_city')->nullable();
            $table->string('country');
            $table->string('ar_country')->nullable();
            $table->text('address');
            $table->text('ar_address')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('type')->default('Branch Office');
            $table->string('image')->nullable();
            $table->string('map_url')->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('hours')->nullable();
            $table->string('ar_hours')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
