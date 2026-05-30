<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('logo_text')->nullable();
            $table->string('beneficiary_en');
            $table->string('beneficiary_ar')->nullable();
            $table->string('account_number');
            $table->string('iban')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
