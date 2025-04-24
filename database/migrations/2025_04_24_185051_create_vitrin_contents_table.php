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
        Schema::create('vitrin_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vitrin_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['blog', 'about', 'gallery']);
            $table->string('title');
            $table->text('content');
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vitrin_contents');
    }
};
