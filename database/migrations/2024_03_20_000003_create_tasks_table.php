<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnel')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_date');
            $table->string('priority')->default('medium');
            $table->string('status')->default('pending');
            $table->foreignId('assigned_by')->constrained('personnel')->onDelete('cascade');
            $table->dateTime('completed_at')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
}; 