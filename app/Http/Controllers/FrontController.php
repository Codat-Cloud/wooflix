<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HomeSection;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Wholesale;
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
        $categories = Category::with('petType')
            ->whereIn(
                'id',
                array_unique(array_merge($categoryIds, $dealCategoryIds))
            )
            ->get()
            ->keyBy('id');


        // 5. THE BIG OPTIMIZATION: Fetch ALL featured products for ALL deal tabs in ONE query
        // Instead of querying inside the loop, we get them all now and group them by category_id
        $uniqueDealCategoryIds = array_unique($dealCategoryIds);

        $allFeaturedProducts = Product::with(['brand', 'variants', 'defaultVariant', 'categories']) // 🟢 Eager load categories
            ->whereHas('categories', function ($q) use ($uniqueDealCategoryIds) {
                // 🟢 Filter products that belong to any of the target deal categories
                $q->whereIn('categories.id', $uniqueDealCategoryIds);
            })
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->get()
            /*
    |--------------------------------------------------------------------------
    | MULTI-CATEGORY GROUPING MATRIX
    |--------------------------------------------------------------------------
    | Since a product can have multiple categories, we cycle through its 
    | categories. If it matches a deal ID, we map it out so it accurately 
    | groups by that category ID key in your final Blade output loop.
    */
            ->flatMap(function ($product) use ($uniqueDealCategoryIds) {
                return $product->categories
                    ->whereIn('id', $uniqueDealCategoryIds)
                    ->map(fn($category) => [
                        'category_id' => $category->id,
                        'product' => $product
                    ]);
            })
            ->groupBy('category_id')
            ->map(fn($group) => $group->pluck('product'));

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
        $query = Product::query()->where('is_active', true)->with('defaultVariant');

        // 2. Apply Filters (We will implement the logic for these later)
        if ($request->filled('brand')) {

            $brands = array_filter(
                explode(',', $request->brand)
            );

            $query->whereHas('brand', function ($q) use ($brands) {
                $q->whereIn('slug', $brands);
            });
        }

        // 3. Fetch Data
        $products = $query->latest()->paginate(1);

        // 4. Get Dynamic Filter Data (for the sidebar)
        $brands = Brand::withCount('products')->get();
        $categories = Category::withCount('products')->get();

        return view('front.shop', compact('products', 'brands', 'categories'));
    }

    public function singleProduct($product_slug,  $variant_slug = null)
    {
        // dd($variant_slug);
        $product = Product::where('slug', $product_slug)
            ->where('is_active', true)
            ->with(['brand', 'categories', 'galleryImages', 'infographicImages', 'variants', 'variants.optionValues', 'reviews', 'defaultVariant', 'frequentlyBought'])
            ->firstOrFail();

        // dd($product);

        // 
        $selectedVariant = null;

        // If variant exists in URL
        if ($variant_slug) {

            $selectedVariant = $product->variants()
                ->where('slug', $variant_slug)
                ->where('is_active', true)
                ->first();
        }

        // Fallback to default variant
        if (!$selectedVariant) {

            $selectedVariant = $product->variants
                ->firstWhere('is_default', true)

                ?? $product->variants->first();
        }

        abort_if(!$selectedVariant, 404);

        $categoryIds = $product->categories->pluck('id')->toArray();

        $relatedProducts = Product::whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        })
            ->where('id', '!=', $product->id) // Exclude the current product
            ->where('is_active', true)
            ->with('defaultVariant')
            ->take(10)
            ->get();

        // Fetch coupons marked as visible and currently valid
        $coupons = Coupon::available()
            ->where('is_visible', true)
            ->orderBy('is_best', 'desc') // Show 'BEST' coupons first
            ->get();

        return view('front.singleProduct', compact('product', 'relatedProducts', 'coupons', 'selectedVariant'));
    }

    public function cart()
    {

        if (!auth()->check()) {
            return redirect('/login');
        }

        $userId = auth()->id();

        // dd($sessionId);

        $items = CartItem::with(['product', 'variant'])
            ->where('user_id', $userId)
            ->get();

        return view('front.cart', compact('items'));
    }

    public function checkout()
    {
        $sessionId = session()->getId();

        $hasItems = CartItem::when(
            auth()->check(),
            fn($q) => $q->where('user_id', auth()->id()),
            fn($q) => $q->where('session_id', $sessionId)
        )->exists();

        if (!$hasItems) {
            return redirect()
                ->route('front.shop')
                ->with('error', 'Your cart is empty.');
        }

        return view('front.checkout');
    }

    public function wholesale()
    {
        return view('front.wholesale');
    }

    public function wholesaleSave(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required',
            'business_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'business_type' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
        ]);

        Wholesale::create([
            ...$data,
            'products_interested' => $request->products_interested,
            'sales_channels' => $request->sales_channels,
            'monthly_quantity' => $request->monthly_quantity,
            'sells_pet_products' => $request->sells_pet_products,
            'brands' => $request->brands,
            'message' => $request->message,
            'consent' => $request->consent ?? false,
        ]);

        return back()->with('success', 'Request submitted');
    }
    public function blogsView($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->with('categories')
            ->firstOrFail();

        // Fetch related posts if the IDs exist in the JSON array
        $relatedPosts = !empty($blog->related_posts)
            ? Blog::whereIn('id', $blog->related_posts)->where('is_published', true)->get()
            : collect();

        return view('front.blogs.view', compact('blog', 'relatedPosts'));
    }
}
