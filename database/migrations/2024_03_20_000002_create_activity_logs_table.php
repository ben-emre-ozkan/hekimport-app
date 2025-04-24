<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnel')->onDelete('cascade');
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('related_model')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
}; 