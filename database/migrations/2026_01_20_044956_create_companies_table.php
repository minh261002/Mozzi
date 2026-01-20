<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('en_name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('en_short_name')->nullable();
            $table->string('tax_code');
            $table->string('legal_address');
            $table->string('en_legal_address')->nullable();
            $table->string('province', 10)->nullable();
            $table->string('ward', 10)->nullable();
            $table->string('representative');
            $table->string('en_representative')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('currency', 10)->default('VND');
            $table->string('timezone', 50)->default('Asia/Ho_Chi_Minh');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
