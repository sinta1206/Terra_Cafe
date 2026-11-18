<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        // Mengirim data settings secara otomatis ke SELURUH file view (.blade.php) termasuk layout utama
        View::composer('*', function ($view) {
            $settings = Setting::all()->pluck('value', 'key');
            $view->with('settings', $settings);
        });
    }
}
