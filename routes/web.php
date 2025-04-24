<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VitrinViewController;
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
use App\Http\Controllers\VitrinController;
use App\Http\Controllers\VitrinBlogController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ClinicConnectionController;
use App\Http\Controllers\SharedResourceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentVitrinController;
use App\Http\Controllers\SeoSettingsController;
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
    Route::get('/profile/{doctor}', [DoctorController::class, 'publicProfile'])->name('doctor.public-profile');
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
    Route::middleware(['auth', 'doctor'])->group(function () {
        // Dashboard
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
            ->name('doctor.dashboard');

        // Appointments
        Route::prefix('doctor/appointments')->name('doctor.appointments.')->group(function () {
            Route::get('/', [DoctorAppointmentController::class, 'index'])->name('index');
            Route::get('/{appointment}', [DoctorAppointmentController::class, 'show'])->name('show');
            Route::patch('/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('update-status');
        });

        // Patients
        Route::prefix('doctor/patients')->name('doctor.patients.')->group(function () {
            Route::get('/', [DoctorPatientController::class, 'index'])->name('index');
            Route::get('/create', [DoctorPatientController::class, 'create'])->name('create');
            Route::post('/', [DoctorPatientController::class, 'store'])->name('store');
            Route::get('/{patient}', [DoctorPatientController::class, 'show'])->name('show');
            Route::get('/{patient}/edit', [DoctorPatientController::class, 'edit'])->name('edit');
            Route::put('/{patient}', [DoctorPatientController::class, 'update'])->name('update');
        });

        // Profile
        Route::prefix('doctor/profile')->name('doctor.profile.')->group(function () {
            Route::get('/', [DoctorProfileController::class, 'edit'])->name('edit');
            Route::put('/', [DoctorProfileController::class, 'update'])->name('update');
        });
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
        } elseif (auth()->user()->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        return redirect()->route('patient.dashboard');
    })->name('dashboard');

    // SEO Settings
    Route::resource('seo-settings', SeoSettingsController::class);
});

// Subdomain routes for vitrin pages
Route::domain('{subdomain}.hekimport.com')->group(function () {
    Route::get('/', [VitrinViewController::class, 'home'])->name('vitrin.home');
    Route::get('/about', [VitrinViewController::class, 'about'])->name('vitrin.about');
    Route::get('/contact', [VitrinViewController::class, 'contact'])->name('vitrin.contact');
    Route::get('/blog', [VitrinViewController::class, 'blog'])->name('vitrin.blog');
    Route::get('/blog/{slug}', [VitrinViewController::class, 'blogShow'])->name('vitrin.blog.show');
});

// Preview route for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/preview/{vitrinId}', [VitrinViewController::class, 'preview'])->name('vitrin.preview');
});

// Vitrin Management Routes
Route::middleware(['auth', 'clinic'])->prefix('vitrin/manage')->name('vitrin.manage.')->group(function () {
    // General Management
    Route::get('/', [VitrinController::class, 'index'])->name('index');
    Route::get('/edit', [VitrinController::class, 'edit'])->name('edit');
    Route::put('/update', [VitrinController::class, 'update'])->name('update');
    Route::get('/preview', [VitrinController::class, 'preview'])->name('preview');
    Route::post('/publish', [VitrinController::class, 'publish'])->name('publish');
    Route::post('/unpublish', [VitrinController::class, 'unpublish'])->name('unpublish');
    Route::get('/statistics', [VitrinController::class, 'statistics'])->name('statistics');
    
    // Media Management
    Route::get('/media', [VitrinController::class, 'media'])->name('media');
    Route::post('/media/upload', [VitrinController::class, 'uploadMedia'])->name('media.upload');
    Route::delete('/media/{id}', [VitrinController::class, 'deleteMedia'])->name('media.delete');
    
    // Blog Management
    Route::resource('blog', VitrinBlogController::class);
    Route::post('/blog/{blog}/publish', [VitrinBlogController::class, 'publish'])->name('blog.publish');
    Route::post('/blog/{blog}/unpublish', [VitrinBlogController::class, 'unpublish'])->name('blog.unpublish');
    Route::post('/blog/upload-image', [VitrinBlogController::class, 'uploadImage'])->name('blog.upload-image');
});

// Personnel Management Routes
Route::middleware(['auth', 'clinic'])->prefix('clinic')->name('clinic.')->group(function () {
    // Personnel Routes
    Route::resource('personnel', PersonnelController::class);
    Route::post('personnel/{personnel}/resend-invitation', [PersonnelController::class, 'resendInvitation'])->name('personnel.resend-invitation');
    Route::post('personnel/{personnel}/permissions', [PersonnelController::class, 'updatePermissions'])->name('personnel.permissions');
    Route::post('personnel/{personnel}/working-hours', [PersonnelController::class, 'updateWorkingHours'])->name('personnel.working-hours');
    Route::post('personnel/{personnel}/onboarding-progress', [PersonnelController::class, 'updateOnboardingProgress'])->name('personnel.onboarding-progress');

    // Task Routes
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::get('tasks/export', [TaskController::class, 'export'])->name('tasks.export');
});

// Invitation Routes
Route::get('invitation/{token}', [InvitationController::class, 'showAcceptForm'])->name('invitation.show');
Route::post('invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');

// Clinic Routes
Route::middleware(['auth'])->group(function () {
    // Clinic Management
    Route::resource('clinics', ClinicController::class);
    Route::get('clinics/{clinic}/dashboard', [ClinicController::class, 'dashboard'])->name('clinics.dashboard');
    Route::get('clinics/{clinic}/settings', [ClinicController::class, 'settings'])->name('clinics.settings');
    Route::put('clinics/{clinic}/settings', [ClinicController::class, 'updateSettings'])->name('clinics.settings.update');

    // Clinic Connections
    Route::post('clinics/{clinic}/connect', [ClinicConnectionController::class, 'connect'])->name('clinics.connect');
    Route::post('clinics/{clinic}/disconnect', [ClinicConnectionController::class, 'disconnect'])->name('clinics.disconnect');
    Route::post('clinics/{clinic}/members/{user}/approve', [ClinicConnectionController::class, 'approve'])->name('clinics.members.approve');
    Route::post('clinics/{clinic}/members/{user}/reject', [ClinicConnectionController::class, 'reject'])->name('clinics.members.reject');
    Route::put('clinics/{clinic}/members/{user}/role', [ClinicConnectionController::class, 'updateRole'])->name('clinics.members.role');
    Route::post('clinics/{clinic}/members/{user}/suspend', [ClinicConnectionController::class, 'suspend'])->name('clinics.members.suspend');
    Route::post('clinics/{clinic}/members/{user}/reactivate', [ClinicConnectionController::class, 'reactivate'])->name('clinics.members.reactivate');

    // Shared Resources
    Route::resource('clinics.shared-resources', SharedResourceController::class);
    Route::post('clinics/{clinic}/shared-resources/{resource}/share', [SharedResourceController::class, 'share'])->name('clinics.shared-resources.share');
    Route::post('clinics/{clinic}/shared-resources/{resource}/unshare', [SharedResourceController::class, 'unshare'])->name('clinics.shared-resources.unshare');
});

// Student Portal (Akademi) Routes
Route::prefix('akademi')->name('akademi.')->group(function () {
    // Authentication
    Route::get('register', [StudentController::class, 'register'])->name('register');
    Route::post('register', [StudentController::class, 'store'])->name('store');

    // Dashboard & Profile
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('profile/edit', [StudentController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [StudentController::class, 'update'])->name('profile.update');
    });

    // Vitrin Management
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('vitrin/edit', [StudentVitrinController::class, 'edit'])->name('vitrin.edit');
        Route::put('vitrin', [StudentVitrinController::class, 'update'])->name('vitrin.update');
        Route::post('vitrin/media', [StudentVitrinController::class, 'uploadMedia'])->name('vitrin.media.upload');
        Route::delete('vitrin/media', [StudentVitrinController::class, 'removeMedia'])->name('vitrin.media.remove');
        Route::get('vitrin/preview', [StudentVitrinController::class, 'preview'])->name('vitrin.preview');
    });

    // Student Directory
    Route::get('directory', [StudentController::class, 'directory'])->name('directory');
});

// Subdomain Routes for Student Vitrins
Route::domain('{subdomain}.' . config('app.akademi_domain'))->group(function () {
    Route::get('/', [StudentVitrinController::class, 'show'])->name('vitrin.show');
});