<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
        $this->configureRedirectPath();
    }

    /**
     * Configure the redirect path after login based on user role.
     */
    protected function configureRedirectPath(): void
    {
        Route::macro('redirectBasedOnRole', function () {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->role === 'agent') {
                    return redirect()->route('agent.dashboard');
                } elseif ($user->role === 'supervisor') {
                    return redirect()->route('supervisor.dashboard');
                }
            }
            return redirect('/dashboard'); // Fallback to default dashboard
        });
    }
}
