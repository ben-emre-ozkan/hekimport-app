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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('university');
            $table->year('graduation_year');
            $table->string('student_id')->nullable();
            $table->text('bio')->nullable();
            $table->json('interests')->nullable();
            $table->json('skills')->nullable();
            $table->json('social_links')->nullable();
            $table->json('privacy_settings')->nullable();
            $table->string('subdomain')->unique();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
}; 