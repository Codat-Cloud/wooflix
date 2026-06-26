<?php

namespace App\Livewire\Front;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductFilterTag;
use App\Models\ProductVariant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

class ProductGrid extends Component
{
    use WithPagination;

    protected $listeners = [
        'add-to-cart' => 'add',
        'cart-updated' => 'loadCartVariantIds'
    ];

    // Scoped layout multi-mode parameters
    public string $mode = 'all'; // Options: 'all', 'fbt', 'related'
    public ?int $parentProductId = null;
    public ?int $categoryId = null;

    #[Url(as: 'tags', except: '')]
    public $tags = '';

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'brand', except: '')]
    public $brand = '';

    #[Url(as: 'cat', except: '')]
    public $cat = '';

    #[Url(as: 'sort')]
    public $sort = 'best_seller';

    public array $selectedTags = [];
    public array $selectedBrands = [];
    public array $selectedCategories = [];

    public $perPage = 12;

    public $selectedVariants = [];

    protected $cart;

    public $cartVariantIds = [];

    public function mount()
    {
        $this->loadCartVariantIds();

        // Dynamic configuration boundaries based on display mode
        if ($this->mode === 'all') {
            $this->hydrateFilters();
        } else {
            $this->perPage = 4; // Caps slider rows to a compact list of cross-sells
        }
    }

    public function boot(Cart $cart)
    {
        $this->cart = $cart;
    }

    protected function hydrateFilters(): void
    {
        $this->selectedTags = $this->parseQueryString($this->tags);

        $this->selectedBrands = $this->parseQueryString($this->brand);

        $this->selectedCategories = $this->parseQueryString($this->cat);
    }

    public function updatedTags()
    {
        $this->selectedTags = $this->parseQueryString($this->tags);

        $this->resetPage();
    }

    public function updatedBrand()
    {
        $this->selectedBrands = $this->parseQueryString($this->brand);

        $this->resetPage();
    }

    public function updatedCat()
    {
        $this->selectedCategories = $this->parseQueryString($this->cat);

        $this->resetPage();
    }

    public function loadCartVariantIds()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $this->cartVariantIds = \App\Models\CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })
            ->pluck('variant_id')
            ->filter()
            ->map(fn($id) => (int)$id)
            ->toArray();
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

    protected function toggleValue(string $current, string $slug): string
    {
        $items = $this->parseQueryString($current);

        if (in_array($slug, $items)) {
            $items = array_values(array_diff($items, [$slug]));
        } else {
            $items[] = $slug;
        }

        // return implode(',', array_unique($items));

        $items = array_unique(array_filter($items));

        return count($items)
            ? implode(',', $items)
            : '';
    }

    public function toggleTag($slug)
    {
        $this->tags = $this->toggleValue($this->tags, $slug);

        $this->selectedTags = $this->parseQueryString($this->tags);

        $this->resetPage();
    }

    public function toggleBrand($slug)
    {
        $this->brand = $this->toggleValue($this->brand, $slug);

        $this->selectedBrands = $this->parseQueryString($this->brand);

        $this->resetPage();
    }

    public function toggleCategory($slug)
    {
        $this->cat = $this->toggleValue($this->cat, $slug);

        $this->selectedCategories = $this->parseQueryString($this->cat);

        $this->resetPage();
    }

    protected function parseQueryString(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return array_values(
            array_filter(
                explode(',', $value)
            )
        );
    }

    public function render()
    {
        $seoTitle = "Best store to buy pet products";
        $query = Product::query()->where('is_active', true)->with(['brand', 'variants', 'defaultVariant']);

        /*
        |--------------------------------------------------------------------------
        | MULTI-MODE CONSTRAINTS INTERCEPTOR
        |--------------------------------------------------------------------------
        */
        if ($this->mode === 'fbt' && $this->parentProductId) {
            // 🔍 FIXED: Run a direct database subquery check against your many-to-many cross-sell table
            $query->whereIn('products.id', function ($subQuery) {
                $subQuery->select('related_product_id')
                    ->from('product_frequently_bought')
                    ->where('product_id', $this->parentProductId);
            });
        } elseif ($this->mode === 'related' && $this->categoryId) {
            // Pull items from the same category, excluding the current product view ID
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->categoryId);
            })->where('products.id', '!=', $this->parentProductId);
        } else {

            // Search Query
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
        }

        // Filter Tags
        // Get tags directly from the property to ensure we have the latest data

        $currentTagSlugs = $this->selectedTags;

        if (!empty($currentTagSlugs)) {
            // Find IDs and Types for the slugs provided
            $tagsFromDb = ProductFilterTag::whereIn('slug', $currentTagSlugs)
                ->active()
                ->get(['id', 'type']);

            $query->where(function ($q) use ($tagsFromDb) {
                foreach ($tagsFromDb as $tag) {
                    // IMPORTANT: Use (int) because your JSON stores numbers [3]
                    $q->whereJsonContains("filters->{$tag->type}", (int)$tag->id);
                }
            });
        }

        // Filter Logic - Ensure we have a valid array
        $selectedBrands = (array) $this->selectedBrands;

        // dd($selectedBrands);

        if (count($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        $selectedCategories = (array) $this->selectedCategories;

        if (count($selectedCategories)) {
            $query->whereHas('categories', function ($q) use ($selectedCategories) {
                $q->where(function ($innerQuery) use ($selectedCategories) {
                    $innerQuery->whereIn('categories.slug', $selectedCategories)
                        ->orWhereIn('categories.parent_id', function ($subQuery) use ($selectedCategories) {
                            $subQuery->select('id')
                                ->from('categories')
                                ->whereIn('slug', $selectedCategories);
                        });
                });
            });
        }

        // Sorting
        $query = match ($this->sort) {
            'price_low'  => $query->orderBy(
                ProductVariant::select('sale_price')
                    ->whereColumn('product_id', 'products.id')
                    ->where('is_default', true)
                    ->limit(1),
                'asc'
            ),
            'price_high' => $query->orderBy(
                ProductVariant::select('sale_price')
                    ->whereColumn('product_id', 'products.id')
                    ->where('is_default', true)
                    ->limit(1),
                'desc'
            ),
            'newest'  => $query->latest(),
            'rating'  => $query->orderBy('rating', 'desc'),
            default   => $query->orderByDesc('is_featured'),
        };

        // Dispatch browser event for dynamic title
        $this->dispatch('page-title-updated', title: $seoTitle);

        foreach ($query->get() as $product) {

            if (
                $product->variants->count() === 1 &&
                !isset($this->selectedVariants[$product->id])
            ) {
                $this->selectedVariants[$product->id] =
                    $product->variants->first()->id;
            }
        }

        return view('livewire.front.product-grid', [
            'products' => $query->paginate($this->perPage),
            'brands' => $this->mode === 'all' ? Brand::withCount('products')->get() : collect(),
            'categories' => $this->mode === 'all' ? Category::whereNull('parent_id')->withCount('products')->get() : collect(),
            'filterGroups' => $this->mode === 'all' ? ProductFilterTag::grouped() : [],
            'seoTitle' => $seoTitle,
            'selectedBrands' => $this->selectedBrands,
            'selectedCategories' => $this->selectedCategories,
            'selectedTags' => $this->selectedTags,
        ]);
    }

    public function clearAll()
    {
        $this->brand = '';
        $this->cat = '';
        $this->tags = '';
        $this->sort = 'best_seller';
        $this->perPage = 12;

        $this->resetPage();

        $this->dispatch('page-title-updated', title: 'Shop Pet Supplies');
    }

    public function addToCart($productId, Cart $cart)
    {
        $product = Product::with('variants')->find($productId);
        if (!$product) return;

        $variantId = $this->selectedVariants[$productId] ?? null;

        if ($product->variants->isNotEmpty()) {
            $variantId = $variantId ?? $product->variants->first()->id;
            // Pass both IDs to match your Cart component's expected signature
            $cart->add(['variant_id' => $variantId, 'product_id' => $productId]);
        } else {
            $cart->add(null, $productId);
        }

        // Refresh local list of IDs
        $this->loadCartVariantIds();

        $this->dispatch('cart-updated');

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Added to your paw-basket!']);
    }
}
