<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Vitrin;
use App\Policies\AppointmentPolicy;
use App\Policies\PatientPolicy;
use App\Policies\VitrinPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Patient::class => PatientPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        Vitrin::class => VitrinPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for role-based access
        Gate::define('manage-patients', function (User $user) {
            return $user->isDoctor() || $user->isPersonel();
        });

        Gate::define('manage-appointments', function (User $user) {
            return $user->isDoctor() || $user->isPersonel();
        });

        Gate::define('manage-vitrin', function (User $user) {
            return $user->isDoctor() || $user->isStudent();
        });

        Gate::define('manage-clinic', function (User $user) {
            return $user->isDoctor();
        });

        Gate::define('view-academy', function (User $user) {
            return $user->isStudent();
        });
    }
} 