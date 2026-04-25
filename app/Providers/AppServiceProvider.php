<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\SiteSetting;
use App\Observers\BlogObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

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
        View::composer('*', function ($view) {
            $brands = Brand::select('name', 'slug', 'logo')
                ->where('is_visible', '1')
                ->latest()
                ->take(6)
                ->get();

            $view->with('brands', $brands);
        });

        Blog::observe(BlogObserver::class);
        view()->composer('*', function ($view) {
            $view->with('settings', SiteSetting::pluck('value', 'key')->toArray());
        });
    }
}
