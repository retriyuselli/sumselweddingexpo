<?php

namespace App\Providers;

use App\Models\Penyelenggara;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.navbar', function ($view) {
            $penyelenggara = Penyelenggara::query()
                ->select('name', 'logo')
                ->first();

            $view->with('penyelenggara', $penyelenggara);
        });
    }
}
