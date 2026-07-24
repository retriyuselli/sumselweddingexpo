<?php

namespace App\Providers;

use App\Models\Penyelenggara;
use App\Services\ExpoResolver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\Illuminate\Foundation\Vite::class, \App\Support\Vite::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Log Viewer: hanya bisa diakses oleh super_admin dan admin
        Gate::define('viewLogViewer', function ($user) {
            return $user->hasAnyRole(['super_admin', 'admin']);
        });

        View::composer('layouts.navbar', function ($view) {
            $penyelenggara = Cache::remember('penyelenggara.navbar', 600, function () {
                return Penyelenggara::query()
                    ->select('name', 'logo')
                    ->first();
            });

            $view->with('penyelenggara', $penyelenggara);
        });

        // Invalidate cached expo / navbar when related models change
        \App\Models\Expo::saved(fn () => ExpoResolver::forgetNearest());
        \App\Models\Expo::deleted(fn () => ExpoResolver::forgetNearest());
        Penyelenggara::saved(function () {
            Cache::forget('penyelenggara.navbar');
            Cache::forget('penyelenggara.brand');
        });
        Penyelenggara::deleted(function () {
            Cache::forget('penyelenggara.navbar');
            Cache::forget('penyelenggara.brand');
        });
    }
}
