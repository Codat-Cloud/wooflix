<?php

namespace App\Livewire\Front;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

class ProductGrid extends Component
{
    use WithPagination;

    protected $listeners = ['add-to-cart' => 'add'];

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'brand')]
    public $brand = '';

    #[Url(as: 'cat')]
    public $cat = '';

    #[Url(as: 'sort')]
    public $sort = 'best_seller';

    public $perPage = 12;

    public $selectedVariants = [];
    protected $cart;

    public function boot(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getSelectedBrandsProperty()
    {
        // Ensure we always return an array, even if empty
        return array_filter(explode(',', $this->brand));
    }

    public function getSelectedCategoriesProperty()
    {
        // Ensure we always return an array, even if empty
        return array_filter(explode(',', $this->cat));
    }

    public function toggleBrand($slug)
    {
        // Use the computed property directly to get the current list
        $brands = $this->selectedBrands;

        if (in_array($slug, $brands)) {
            $brands = array_values(array_diff($brands, [$slug]));
        } else {
            $brands[] = $slug;
        }

        // array_filter removes empty strings if the array was empty
        $this->brand = implode(',', array_filter($brands));
        $this->resetPage();
    }

    public function toggleCategory($slug)
    {
        $cats = $this->selectedCategories;

        if (in_array($slug, $cats)) {
            $cats = array_values(array_diff($cats, [$slug]));
        } else {
            $cats[] = $slug;
        }

        $this->cat = implode(',', array_filter($cats));
        $this->resetPage();
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

    #[Computed]
    public function selectedBrands()
    {
        return array_filter(explode(',', $this->brand));
    }

    #[Computed]
    public function selectedCategories()
    {
        return array_filter(explode(',', $this->cat));
    }

    public function render()
    {
        // dd(request()->query('q'), $this->search);

        $seoTitle = "Best store to buy pet products";
        $query = Product::query()
            ->with(['brand', 'variants'])
            ->where('is_active', true);


        if (!empty($this->search)) {

            $keywords = explode(' ', $this->search);

            $query->where(function ($q) use ($keywords) {

                foreach ($keywords as $word) {

                    $word = '%' . $word . '%';

                    $q->where(function ($sub) use ($word) {

                        $sub->where('products.name', 'ILIKE', $word)
                            ->orWhereHas('brand', function ($q) use ($word) {
                                $q->where('name', 'ILIKE', $word);
                            })
                            ->orWhereHas('variants', function ($q) use ($word) {
                                $q->where('name', 'ILIKE', $word);
                            });
                    });
                }
            });
        }

        // Filter Logic - Ensure we have a valid array
        $selectedBrands = $this->getSelectedBrandsProperty();
        if (!empty($selectedBrands) && is_array($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        $selectedCategories = $this->getSelectedCategoriesProperty();
        if (!empty($selectedCategories) && is_array($selectedCategories)) {
            $query->whereHas('category', function ($q) use ($selectedCategories) {
                $q->whereIn('slug', $selectedCategories);
            });
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
            'categories' => Category::whereNull('parent_id')->withCount('products')->get(),
            'seoTitle' => $seoTitle,
            'selectedBrands' => $this->selectedBrands,
            'selectedCategories' => $this->selectedCategories,
        ]);
    }

    public function clearAll()
    {
        $this->brand = '';
        $this->cat = '';
        $this->sort = 'best_seller';
        $this->perPage = 12;

        $this->resetPage();

        $this->dispatch('page-title-updated', title: 'Shop Pet Supplies');
    }

// Add the namespace at the top of your file
// use App\Services\Cart; (Or wherever your Cart class is located)

public function addToCart($productId, Cart $cart) 
{
    $product = \App\Models\Product::with('variants')->find($productId);

    if (!$product) return;

    $variantId = $this->selectedVariants[$productId] ?? null;

    if ($product->variants->isNotEmpty()) {
        // Default to first variant if somehow not set
        $variantId = $variantId ?? $product->variants->first()->id;
        $cart->add($variantId, null);
    } else {
        $cart->add(null, $productId);
    }

    $this->dispatch('cart-updated');
    $this->dispatch('notify', ['type' => 'success', 'message' => 'Added to your paw-basket!']);
}

    public function updatedProducts()
    {
        // Optional: Pre-select first variant for every product on the page
        foreach ($this->products() as $product) {
            if ($product->variants->isNotEmpty() && !isset($this->selectedVariants[$product->id])) {
                $this->selectedVariants[$product->id] = $product->variants->first()->id;
            }
        }
    }
}
