<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->foreignId('shared_by')->constrained('users')->onDelete('cascade');
            $table->json('permissions')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('shared_resource_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shared_resource_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('permissions')->nullable();
            $table->timestamps();

            $table->unique(['shared_resource_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_resource_users');
        Schema::dropIfExists('shared_resources');
    }
}; 