<?php

namespace App\Providers;

use App\Models\User; // Ditambahkan
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Ditambahkan

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Ditambahkan: Mendefinisikan aturan hak akses (Gates)
        
        // Gate untuk Super Admin
        Gate::define('super_admin', function (User $user) {
            return $user->role === 'super_admin';
        });

        // Gate untuk Super Admin & Admin Barang
        Gate::define('manage_items', function (User $user) {
            return in_array($user->role, ['super_admin', 'admin_barang']);
        });
    }
}