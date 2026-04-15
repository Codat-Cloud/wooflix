<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HomeSection;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;


class FrontController extends Controller
{

    public function index()
    {
        // 1. Fetch Global Data
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $offers = Offer::where('is_active', true)->orderBy('sort_order')->get();

        // 2. Fetch Sections with eager-loaded Items
        $sections = HomeSection::where('is_active', true)
            ->with(['items' => fn($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        // 3. Collect all IDs in ONE pass to fetch everything in bulk
        $brandIds = [];
        $categoryIds = [];
        $dealCategoryIds = [];

        foreach ($sections as $section) {
            foreach ($section->items as $item) {
                if (!$item->item_id) continue;
                if ($section->type === 'brand') {
                    $brandIds[] = $item->item_id;
                } elseif ($section->type === 'category') {
                    $categoryIds[] = $item->item_id;
                } elseif ($section->type === 'tabbed_category_products') {
                    $dealCategoryIds[] = $item->item_id;
                }
            }
        }

        // 4. Bulk Fetch Reference Data (Brands & Categories)
        $brands = Brand::whereIn('id', array_unique($brandIds))->get()->keyBy('id');
        $categories = Category::whereIn('id', array_unique(array_merge($categoryIds, $dealCategoryIds)))->get()->keyBy('id');

        // 5. THE BIG OPTIMIZATION: Fetch ALL featured products for ALL deal tabs in ONE query
        // Instead of querying inside the loop, we get them all now and group them by category_id
        $allFeaturedProducts = Product::with(['brand', 'variants'])
            ->whereIn('category_id', array_unique($dealCategoryIds))
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->get()
            ->groupBy('category_id');

        // 6. Map the pre-fetched products back to the section items
        foreach ($sections as $section) {
            if ($section->type === 'tabbed_category_products') {
                foreach ($section->items as $item) {
                    // Pull from our pre-fetched collection instead of hitting the DB again
                    $item->products = $allFeaturedProducts->get($item->item_id, collect())->take(12);
                }
            }
        }

        // Fetch 4 latest published blogs
        $latestBlogs = Blog::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        return view('front.index', compact(
            'banners',
            'offers',
            'sections',
            'brands',
            'categories',
            'latestBlogs'
        ));
    }

    public function shop(Request $request)
    {
        // 1. Start Query
        $query = Product::query()->where('is_active', true);

        // 2. Apply Filters (We will implement the logic for these later)
        if ($request->has('brand')) {
            $query->whereIn('brand_id', $request->brand);
        }

        // 3. Fetch Data
        $products = $query->latest()->paginate(1);

        // 4. Get Dynamic Filter Data (for the sidebar)
        $brands = Brand::withCount('products')->get();
        $categories = Category::withCount('products')->get();

        return view('front.shop', compact('products', 'brands', 'categories'));
    }

    public function singleProduct($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['brand', 'category', 'images', 'variants', 'reviews'])
            ->firstOrFail();

        // Fetch related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(10)
            ->get();

            // Fetch coupons marked as visible and currently valid
        $coupons = Coupon::available()
            ->where('is_visible', true)
            ->orderBy('is_best', 'desc') // Show 'BEST' coupons first
            ->get();

        return view('front.singleProduct', compact('product', 'relatedProducts', 'coupons'));
    }

}
