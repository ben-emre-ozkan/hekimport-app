<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\MasaController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\VitrinController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'doctor'])->group(function () {
    Route::get('/', [MasaController::class, 'index'])->name('index');

    // Vitrin Management
    Route::prefix('vitrin')->name('vitrin.')->group(function () {
        Route::get('/', [VitrinController::class, 'index'])->name('index');
        Route::get('/edit', [VitrinController::class, 'edit'])->name('edit');
        Route::put('/', [VitrinController::class, 'update'])->name('update');
    });

    // Clinic Management
    Route::prefix('clinic')->name('clinic.')->group(function () {
        Route::get('/', [ClinicController::class, 'index'])->name('index');
        Route::get('/connect', [ClinicController::class, 'connect'])->name('connect');
        Route::post('/connect', [ClinicController::class, 'store'])->name('store');
        Route::delete('/disconnect', [ClinicController::class, 'disconnect'])->name('disconnect');
    });

    // Patient Management
    Route::resource('patients', PatientController::class);

    // Appointment Management
    Route::resource('appointments', AppointmentController::class);
}); 