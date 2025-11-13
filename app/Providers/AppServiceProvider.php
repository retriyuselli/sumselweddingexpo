<?php

namespace App\Providers;

use App\Models\Penyelenggara;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.navbar', function ($view) {
            $penyelenggara = Penyelenggara::query()
                ->select('name', 'logo')
                ->first();

            $view->with('penyelenggara', $penyelenggara);
        });
    }
}
