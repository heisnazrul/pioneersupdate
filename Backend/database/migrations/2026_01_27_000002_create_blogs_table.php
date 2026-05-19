<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('ar_title')->nullable();
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->text('ar_summary')->nullable();
            $table->longText('content')->nullable();
            $table->longText('ar_content')->nullable();
            $table->foreignId('category_id')
                ->constrained('blog_categories')
                ->cascadeOnDelete();
            $table->enum('audience_scope', ['university', 'school', 'all'])
                ->default('all');
            $table->string('featured_image')->nullable();
            $table->foreignId('publisher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
