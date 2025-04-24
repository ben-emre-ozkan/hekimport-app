<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('staff');
            $table->string('status')->default('pending');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->json('working_hours')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('onboarding_completed')->default(false);
            $table->json('onboarding_progress')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
}; 