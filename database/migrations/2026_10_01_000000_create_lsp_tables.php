<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certification_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('scheme_code')->unique();
            $table->string('scheme_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('certification_schemes')->cascadeOnDelete();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->text('address')->nullable();
            $table->string('status')->default('Belum Kompeten');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
        Schema::dropIfExists('certification_schemes');
    }
};
