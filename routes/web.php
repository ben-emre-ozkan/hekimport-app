<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Clinic\DashboardController as ClinicDashboardController;
use App\Http\Controllers\Clinic\DoctorController as ClinicDoctorController;
use App\Http\Controllers\Clinic\ServiceController as ClinicServiceController;
use App\Http\Controllers\Clinic\AppointmentController as ClinicAppointmentController;
use App\Http\Controllers\Clinic\ProfileController as ClinicProfileController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\ProfileController as DoctorProfileController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

// Public routes
Route::prefix('doctor')->group(function () {
    Route::get('/search', [DoctorController::class, 'search'])->name('doctor.search');
    Route::get('/{doctor}', [DoctorController::class, 'publicProfile'])->name('doctor.public-profile');
    Route::resource('', DoctorController::class)->names([
        'index' => 'doctor.index',
        'create' => 'doctor.create',
        'store' => 'doctor.store',
        'show' => 'doctor.show',
        'edit' => 'doctor.edit',
        'update' => 'doctor.update',
        'destroy' => 'doctor.destroy',
    ]);
});

// Appointment routes (public)
Route::prefix('appointments')->group(function () {
    Route::post('/book/{doctor}', [AppointmentController::class, 'book'])->name('appointments.book');
    Route::get('/confirm/{appointment}', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::get('/cancel/{appointment}', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// Auth routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware('auth')->group(function () {
    // Common routes for all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Clinic routes
    Route::middleware('clinic')->prefix('clinic')->group(function () {
        Route::get('/dashboard', [ClinicDashboardController::class, 'index'])->name('clinic.dashboard');
        Route::resource('doctors', ClinicDoctorController::class);
        Route::resource('services', ClinicServiceController::class);
        Route::resource('appointments', ClinicAppointmentController::class);
        Route::get('/profile', [ClinicProfileController::class, 'edit'])->name('clinic.profile.edit');
        Route::put('/profile', [ClinicProfileController::class, 'update'])->name('clinic.profile.update');
    });
    
    // Doctor routes
    Route::middleware('doctor')->prefix('doctor')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments');
        Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('doctor.appointments.show');
        Route::get('/patients', [DoctorPatientController::class, 'index'])->name('doctor.patients');
        Route::get('/patients/create', [DoctorPatientController::class, 'create'])->name('doctor.patients.create');
        Route::post('/patients', [DoctorPatientController::class, 'store'])->name('doctor.patients.store');
        Route::get('/patients/{patient}', [DoctorPatientController::class, 'show'])->name('doctor.patients.show');
        Route::get('/patients/{patient}/edit', [DoctorPatientController::class, 'edit'])->name('doctor.patients.edit');
        Route::put('/patients/{patient}', [DoctorPatientController::class, 'update'])->name('doctor.patients.update');
        Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('doctor.profile.edit');
        Route::put('/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');
    });
    
    // Patient routes
    Route::middleware('patient')->prefix('patient')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
        Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('patient.appointments');
        Route::get('/profile', [PatientProfileController::class, 'edit'])->name('patient.profile.edit');
        Route::put('/profile', [PatientProfileController::class, 'update'])->name('patient.profile.update');
    });
    
    // Redirect to appropriate dashboard based on role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'clinic') {
            return redirect()->route('clinic.dashboard');
        }
        return redirect()->route('patient.dashboard');
    })->name('dashboard');
});