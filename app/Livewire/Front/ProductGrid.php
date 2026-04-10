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

    // 2. Add the #[Url] attribute to these properties
    #[Url(history: true)]
    
    // Filter states
    #[Url(as: 'brand', history: true, keep: false)]
    public $selectedBrands = [];

    #[Url(as: 'cat', history: true, keep: false)]
    public $selectedCategories = [];
    
    #[Url(history: true)]
    public $sort = 'best_seller';
    public $perPage = 12;

    // Reset pagination on filter change
    public function updatedSelectedBrands() { $this->resetPage(); $this->perPage = 12; }
    public function updatedSelectedCategories() { $this->resetPage(); $this->perPage = 12; }
    public function updatedSort() { $this->resetPage(); $this->perPage = 12; }

    public function clearAll()
    {
        $this->reset(['selectedBrands', 'selectedCategories', 'sort', 'perPage']);
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function render()
    {
        $query = Product::query()->where('is_active', true);

        // Filter by Brand Slugs
        if (!empty($this->selectedBrands)) {
            $query->whereHas('brand', function($q) {
                $q->whereIn('slug', $this->selectedBrands);
            });
        }

        // Filter by Category Slugs
        if (!empty($this->selectedCategories)) {
            $query->whereHas('category', function($q) {
                $q->whereIn('slug', $this->selectedCategories);
            });
        }

        // Sorting Logic
        $query = match($this->sort) {
            'price_low'  => $query->orderBy('sale_price', 'asc'),
            'price_high' => $query->orderBy('sale_price', 'desc'),
            'newest'     => $query->latest(),
            'rating'     => $query->orderBy('rating', 'desc'),
            default      => $query->orderBy('is_featured', 'desc'), // Best Seller
        };

        return view('livewire.front.product-grid', [
            'products'   => $query->paginate($this->perPage),
            'brands'     => Brand::withCount('products')->get(),
            'categories' => Category::withCount('products')->get(),
        ]);
    }
}