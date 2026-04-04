<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;


class FrontController extends Controller
{

    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $offers = Offer::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // 1. Get all active sections with their items
        $sections = HomeSection::where('is_active', true)
            ->with(['items' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // 2. Initialize ID collectors
        $brandIds = [];
        $categoryIds = [];
        $productIds = [];

        // 3. Loop through sections to gather IDs for batch fetching
        foreach ($sections as $section) {
            foreach ($section->items as $item) {
                if (!$item->item_id) continue; // Skip if ID is missing

                if ($section->type === 'brand') {
                    $brandIds[] = $item->item_id;
                } elseif ($section->type === 'category' || $section->type === 'tabbed_category_products') {
                    $categoryIds[] = $item->item_id;
                } elseif ($section->type === 'product') {
                    $productIds[] = $item->item_id;
                }
            }
        }

        // 4. Clean the arrays (Remove duplicates and nulls)
        $brandIds = array_unique(array_filter($brandIds));
        $categoryIds = array_unique(array_filter($categoryIds));
        $productIds = array_unique(array_filter($productIds));

        // 5. Fetch Data in Bulk (Pre-load relationships to avoid 100s of queries)
        $brands = Brand::whereIn('id', $brandIds)->get()->keyBy('id');
        $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');
        $products = Product::with(['variants', 'brand'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // 6. Handle Tabbed Sections (Injecting products into the items)
        foreach ($sections as $section) {
            if ($section->type === 'tabbed_category_products') {
                foreach ($section->items as $item) {
                    if ($item->item_id) {
                        $item->products = Product::with('brand')
                            ->where('category_id', $item->item_id)
                            ->where('is_active', true)
                            ->latest()
                            ->limit(10)
                            ->get();
                    } else {
                        $item->products = collect(); // Return empty collection if no ID
                    }
                }
            }
        }

        return view('front.index', compact(
            'banners',
            'offers',
            'sections',
            'brands',
            'categories',
            'products'
        ));
    }
}
