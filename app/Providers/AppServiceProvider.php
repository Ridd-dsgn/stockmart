<?php

namespace App\Providers;

use App\Models\User;
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
        Gate::define('manage-master', fn(User $u) => $u->role === 'admin');
        Gate::define('manage-stock',  fn(User $u) => $u->role === 'petugas_gudang');
        Gate::define('view-report',   fn(User $u) => in_array($u->role, ['admin', 'kepala_toko']));
    }
}
