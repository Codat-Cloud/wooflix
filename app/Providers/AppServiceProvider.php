<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
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

            // 🟢 1. FETCH DOG CATEGORIES (Only if they or their children have active products)
            $dogCategories = Category::with(['children' => function ($query) {
                // Only load child categories that have at least one active product
                $query->whereHas('products', function ($q) {
                    $q->where('is_active', true);
                })->select('id', 'parent_id', 'name', 'slug');
            }])
                ->whereNull('parent_id')
                ->whereHas('petType', function ($query) {
                    $query->whereIn('slug', ['dog', 'dogs']);
                })
                ->where(function ($query) {
                    // Enforce: Parent must have active products OR its children must have active products
                    $query->whereHas('products', function ($q) {
                        $q->where('is_active', true);
                    })->orWhereHas('children.products', function ($q) {
                        $q->where('is_active', true);
                    });
                })
                ->select('id', 'name', 'slug', 'pet_type_tag_id')
                ->get();

            // 🟢 2. FETCH CAT CATEGORIES (Only if they or their children have active products)
            $catCategories = Category::with(['children' => function ($query) {
                // Only load child categories that have at least one active product
                $query->whereHas('products', function ($q) {
                    $q->where('is_active', true);
                })->select('id', 'parent_id', 'name', 'slug');
            }])
                ->whereNull('parent_id')
                ->whereHas('petType', function ($query) {
                    $query->whereIn('slug', ['cat', 'cats']);
                })
                ->where(function ($query) {
                    // Enforce: Parent must have active products OR its children must have active products
                    $query->whereHas('products', function ($q) {
                        $q->where('is_active', true);
                    })->orWhereHas('children.products', function ($q) {
                        $q->where('is_active', true);
                    });
                })
                ->select('id', 'name', 'slug', 'pet_type_tag_id')
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

                'dogCategories' => $dogCategories,
                'catCategories' => $catCategories,

            ]);
        });
    }
}
