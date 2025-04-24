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
        Schema::table('appointments', function (Blueprint $table) {
            // First, make clinic_id nullable
            $table->foreignId('clinic_id')->nullable()->change();
            
            // Add doctor_id if it doesn't exist
            if (!Schema::hasColumn('appointments', 'doctor_id')) {
                $table->foreignId('doctor_id')->after('id')->constrained('users')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable(false)->change();
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }
};
