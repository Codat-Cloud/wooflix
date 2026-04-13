<?php

namespace App\Livewire\Front;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ProductGrid extends Component
{
    use WithPagination;

    // #[Url(as: 'brand', history: true, keep: false)] 
    public $selectedBrands = [];

    // #[Url(as: 'cat', history: true, keep: false)]
    public $selectedCategories = [];

    // #[Url(history: true, keep: false)]
    public $sort = 'best_seller';

    public $perPage = 12;

    public function updatedSelectedBrands()
    {
        $this->resetPage();
        $this->perPage = 12;
    }
    public function updatedSelectedCategories()
    {
        $this->resetPage();
        $this->perPage = 12;
    }
    public function updatedSort()
    {
        $this->resetPage();
        $this->perPage = 12;
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function render()
    {
        $seoTitle = "Shop Pet Supplies";
        $query = Product::query()->where('is_active', true);

        // Filter Logic
        if (!empty($this->selectedBrands)) {
            $query->whereHas('brand', fn($q) => $q->whereIn('slug', $this->selectedBrands));

            if (count($this->selectedBrands) === 1) {
                $brandName = Brand::where('slug', $this->selectedBrands[0])->value('name');
                $seoTitle = "Premium {$brandName} Products";
            }
        }

        if (!empty($this->selectedCategories)) {
            $query->whereHas('category', fn($q) => $q->whereIn('slug', $this->selectedCategories));

            if (count($this->selectedCategories) === 1 && $seoTitle === "Shop Pet Supplies") {
                $catName = Category::where('slug', $this->selectedCategories[0])->value('name');
                $seoTitle = "Best {$catName} for Pets";
            }
        }

        // Sorting
        $query = match ($this->sort) {
            'price_low'  => $query->orderBy('sale_price', 'asc'),
            'price_high' => $query->orderBy('sale_price', 'desc'),
            'newest'     => $query->latest(),
            'rating'     => $query->orderBy('rating', 'desc'),
            default      => $query->orderBy('is_featured', 'desc'),
        };

        // Dispatch browser event for dynamic title
        $this->dispatch('page-title-updated', title: $seoTitle);

        return view('livewire.front.product-grid', [
            'products' => $query->paginate($this->perPage),
            'brands' => Brand::withCount('products')->get(),
            'categories' => Category::withCount('products')->get(),
            'seoTitle' => $seoTitle
        ]);
    }

    public function clearAll()
    {
        // 1. Reset all component properties to their initial state
        $this->selectedBrands = [];
        $this->selectedCategories = [];
        $this->sort = 'best_seller';
        $this->perPage = 12;

        // 2. Clear the session keys
        session()->forget(['shop_brands', 'shop_cats', 'shop_sort']);

        // 3. Reset Pagination to page 1
        $this->resetPage();
        
        // 4. Optional: Dispatch the title update back to default
        $this->dispatch('page-title-updated', title: 'Shop Pet Supplies');
    }
}
