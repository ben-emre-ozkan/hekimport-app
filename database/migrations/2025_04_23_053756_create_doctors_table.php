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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            $table->string('address')->nullable();
            $table->string('specialty')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('available_days')->nullable();
            $table->json('available_times')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};