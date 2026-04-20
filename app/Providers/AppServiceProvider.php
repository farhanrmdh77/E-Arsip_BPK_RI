<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <-- TAMBAHKAN INI

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mendefinisikan aturan: Hanya user dengan role 'admin' yang boleh lewat
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}