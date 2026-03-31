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

        $sections = HomeSection::where('is_active', true)
            ->with('items')
            ->orderBy('sort_order')
            ->get();

        // Collect IDs
        $brandIds = [];
        $categoryIds = [];
        $productIds = [];

        foreach ($sections as $section) {
            foreach ($section->items as $item) {
                if ($section->type === 'brand') {
                    $brandIds[] = $item->item_id;
                }

                if ($section->type === 'category') {
                    $categoryIds[] = $item->item_id;
                }

                if ($section->type === 'product') {
                    $productIds[] = $item->item_id;
                }
            }
        }

        // Fetch once (IMPORTANT)
        $brands = Brand::whereIn('id', $brandIds)->get()->keyBy('id');
        $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');
        $products = Product::with('variants')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        foreach ($sections as $section) {

            if ($section->type === 'tabbed_category_products') {

                foreach ($section->items as $item) {

                    $item->products = \App\Models\Product::with('brand')
                        ->where('category_id', $item->item_id)
                        ->where('is_active', true)
                        ->latest()
                        ->limit(10)
                        ->get();
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
