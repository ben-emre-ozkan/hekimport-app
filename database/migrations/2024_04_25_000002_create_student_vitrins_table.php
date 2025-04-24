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
        Schema::create('student_vitrins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('template')->default('default');
            $table->string('title');
            $table->string('tagline')->nullable();
            $table->text('about')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->json('contact_info')->nullable();
            $table->json('achievements')->nullable();
            $table->json('publications')->nullable();
            $table->json('certifications')->nullable();
            $table->json('custom_sections')->nullable();
            $table->json('seo_metadata')->nullable();
            $table->json('theme_settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_vitrins');
    }
}; 