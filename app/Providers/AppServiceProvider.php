<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FooterLinkGroup;
use App\Models\Page;
use App\Models\ProductFilterTag;
use App\Models\SiteSetting;
use App\Observers\BlogObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            // Memoize per-request to avoid re-querying on every sub-view/component
            static $composerData = null;

            if ($composerData === null) {
                $allSettings = Cache::rememberForever('site_settings_all', function () {
                    return SiteSetting::pluck('value', 'key')->toArray();
                });

                $dogCategories = Category::with(['children' => function ($query) {
                    $query->whereHas('products', function ($q) {
                        $q->where('is_active', true);
                    })->select('id', 'parent_id', 'name', 'slug');
                }])
                    ->whereNull('parent_id')
                    ->whereHas('petType', function ($query) {
                        $query->whereIn('slug', ['dog', 'dogs']);
                    })
                    ->where(function ($query) {
                        $query->whereHas('products', function ($q) {
                            $q->where('is_active', true);
                        })->orWhereHas('children.products', function ($q) {
                            $q->where('is_active', true);
                        });
                    })
                    ->select('id', 'name', 'slug', 'pet_type_tag_id')
                    ->get();

                $catCategories = Category::with(['children' => function ($query) {
                    $query->whereHas('products', function ($q) {
                        $q->where('is_active', true);
                    })->select('id', 'parent_id', 'name', 'slug');
                }])
                    ->whereNull('parent_id')
                    ->whereHas('petType', function ($query) {
                        $query->whereIn('slug', ['cat', 'cats']);
                    })
                    ->where(function ($query) {
                        $query->whereHas('products', function ($q) {
                            $q->where('is_active', true);
                        });
                    })
                    ->select('id', 'name', 'slug', 'pet_type_tag_id')
                    ->get();

                $footerGroups = FooterLinkGroup::with(['children' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                }])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->take(3)
                    ->get();

                // Decode delivery service info with sensible fallbacks
                $rawDeliveryInfo = $allSettings['delivery_service_info'] ?? null;

                $deliveryServiceInfo = !empty($rawDeliveryInfo)
                    ? (is_array($rawDeliveryInfo) ? $rawDeliveryInfo : json_decode($rawDeliveryInfo, true))
                    : [
                        ['icon' => 'lightning', 'text' => 'Check delivery availability', 'highlight' => ''],
                        ['icon' => 'truck',     'text' => 'Enter pincode for delivery date', 'highlight' => ''],
                        ['icon' => 'return',    'text' => 'No Exchange & Returns', 'highlight' => ''],
                        ['icon' => 'free',      'text' => 'Enjoy Free Delivery above', 'highlight' => '₹699'],
                    ];

                $composerData = [
                    'brands' => Brand::where('is_visible', '1')
                        ->select('name', 'slug', 'logo')
                        ->latest()
                        ->take(6)
                        ->get(),

                    'socialLinks' => [
                        'facebook'  => $allSettings['facebook_url'] ?? null,
                        'instagram' => $allSettings['instagram_url'] ?? null,
                        'linkedin'  => $allSettings['linkedin_url'] ?? null,
                        'youtube'   => $allSettings['youtube_url'] ?? null,
                        'twitter'   => $allSettings['twitter_url'] ?? null,
                        'pinterest' => $allSettings['pinterest_url'] ?? null,
                    ],

                    'footerPages' => Page::where('is_active', true)
                        ->select('title', 'slug')
                        ->get(),

                    'settings'      => $allSettings,
                    'dogCategories' => $dogCategories,
                    'catCategories' => $catCategories,
                    'footerGroups'  => $footerGroups,

                    'deliveryServiceInfo' => $deliveryServiceInfo,
                ];
            }

            $view->with($composerData);
        });
    }
}
