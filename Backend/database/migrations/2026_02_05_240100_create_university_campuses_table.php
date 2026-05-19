<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('university_campuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name', 150);
            $table->string('ar_name', 200)->nullable();
            $table->string('slug', 180);
            $table->text('address')->nullable();
            $table->text('ar_address')->nullable();

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            $table->boolean('is_online')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['university_id', 'slug']);
            $table->index(['is_online', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_campuses');
    }
};
