<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-users', function ($user) {
            return $user->role === 'Administrador';
        });

        Gate::define('manage-recepcion', function ($user) {
            return in_array($user->role, ['Administrador', 'Recepcionista']);
        });

        Gate::define('manage-limpieza', function ($user) {
            return in_array($user->role, ['Administrador', 'Limpieza']);
        });
    }
}
