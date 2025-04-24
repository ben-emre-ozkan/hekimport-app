<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->json('specialties')->nullable();
            $table->json('working_hours')->nullable();
            $table->json('settings')->nullable();
            $table->string('status')->default('active');
            $table->string('visibility')->default('public');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
}; 