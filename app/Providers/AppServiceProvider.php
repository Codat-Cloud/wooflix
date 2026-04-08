<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\SiteSetting;
use App\Observers\BlogObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
    
    /**
     * Bootstrap any application services.
    */
    public function boot(): void
    {
        Blog::observe(BlogObserver::class);
        view()->composer('*', function ($view) {
            $view->with('settings', SiteSetting::pluck('value', 'key')->toArray());
        });
    }
}
