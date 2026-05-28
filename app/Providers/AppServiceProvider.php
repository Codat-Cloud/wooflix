<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Page;
use App\Models\ProductFilterTag;
use App\Models\SiteSetting;
use App\Observers\BlogObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // 1. Observers
        Blog::observe(BlogObserver::class);

        // 2. Single Global View Composer
        View::composer('*', function ($view) {

            // Cache the full settings array to avoid multiple DB hits per page
            $allSettings = Cache::rememberForever('site_settings_all', function () {
                return SiteSetting::pluck('value', 'key')->toArray();
            });

            $petTypes = ProductFilterTag::with([
                'categories.children'
            ])
                ->where('type', 'pet_type')
                ->whereHas('categories', function ($q) {
                    $q->whereNull('parent_id');
                })
                ->get();

            $view->with([
                // Brands for footer/sidebar
                'brands' => Brand::where('is_visible', '1')
                    ->select('name', 'slug', 'logo')
                    ->latest()
                    ->take(6)
                    ->get(),

                // Specific Social Links Mapping
                'socialLinks' => [
                    'facebook'  => $allSettings['facebook_url'] ?? null,
                    'instagram' => $allSettings['instagram_url'] ?? null,
                    'linkedin'  => $allSettings['linkedin_url'] ?? null,
                    'youtube'   => $allSettings['youtube_url'] ?? null,
                    'twitter'   => $allSettings['twitter_url'] ?? null,
                    'pinterest' => $allSettings['pinterest_url'] ?? null,
                ],

                // Legal/Policy Pages
                'footerPages' => Page::where('is_active', true)
                    ->select('title', 'slug')
                    ->get(),

                // All settings available as $settings['key']
                'settings' => $allSettings,

                'petTypes' => $petTypes,

            ]);
        });
    }
}
