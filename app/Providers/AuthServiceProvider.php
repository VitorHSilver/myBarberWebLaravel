<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Defina policies aqui, se necessário
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('cancel-appointment', function (User $user) {
            $isAdmin = $user->isAdmin(); 
            $isProfessional = $user->isProfessional();
            return $isProfessional || $isAdmin;
        });

        Gate::define('manage-professionals', function (User $user) {
            $isAdmin = $user->isAdmin(); 
            return $isAdmin;
        });

        Gate::define('update-own-appointment', function (User $user, Appointment $appointment) {
            return $user->id === $appointment->user_id;
        });
    }
}
