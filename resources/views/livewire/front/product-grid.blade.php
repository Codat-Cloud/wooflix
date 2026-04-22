<div>
    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
        <div class="container-xxl">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Collections</li>
            </ol>
        </div>
    </nav>

    <div class="shop-topbar">
        <div class="container-xxl">
            <div class="shop-topbar-inner">
                <h5 class="shop-category">All Products</h5>

                <div class="sort-scroll">
                    <button class="filter-btn d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
                        Filter
                    </button>

                    <button wire:click="$set('sort', 'best_seller')" class="sort-btn {{ $sort == 'best_seller' ? 'active' : '' }}">Best Seller</button>
                    <button wire:click="$set('sort', 'price_low')" class="sort-btn {{ $sort == 'price_low' ? 'active' : '' }}">Price: Low to High</button>
                    <button wire:click="$set('sort', 'price_high')" class="sort-btn {{ $sort == 'price_high' ? 'active' : '' }}">Price: High to Low</button>
                    <button wire:click="$set('sort', 'newest')" class="sort-btn {{ $sort == 'newest' ? 'active' : '' }}">Newest</button>
                    {{-- <button wire:click="$set('sort', 'rating')" class="sort-btn {{ $sort == 'rating' ? 'active' : '' }}">Rating</button> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-sidebar">
                    <div class="filter-header">
                        <h5>Filters</h5>
                        <button wire:click="clearAll" class="clear-filters">Clear All</button>
                    </div>

                    @if(count($selectedBrands) > 0)
                    <div class="active-filters">
                        
                        @foreach($selectedBrands as $slug)
                            <span class="filter-chip" wire:click="$set('selectedBrands', {{ json_encode(array_diff($selectedBrands, [$slug])) }})"> 
                                {{ Str::headline($slug) }} ✕ 
                            </span>
                        @endforeach
                        
                    </div>
                    @endif

                    <div class="filter-group">
                        <button class="filter-toggle">Brands <span class="arrow">⌄</span></button>
                        <div class="filter-content">
                            @foreach($brands as $brand)
                            <label class="filter-option">
                                {{-- We now bind the 'slug' to the model --}}
                                <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand->slug }}" />
                                <span class="checkmark"></span>
                                {{ $brand->name }} <span class="count">({{ $brand->products_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group">
                        <button class="filter-toggle">Category <span class="arrow">⌄</span></button>
                        <div class="filter-content">
                            @foreach($categories as $cat)
                            <label class="filter-option">
                                <input type="checkbox" wire:model.live="selectedCategories" value="{{ $cat->slug }}" />
                                <span class="checkmark"></span>
                                {{ $cat->name }} <span class="count">({{ $cat->products_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row g-4" wire:loading.class="opacity-50">
                    @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->discount_percentage)
                                    <span class="product-badge">{{ $product->discount_percentage }}% OFF</span>
                                @endif
                                <a href="{{ route('front.singleProduct', $product->slug) }}">
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
                                </a>
                                @if($product->rating)
                                    <span class="product-rating">⭐ {{ $product->rating }}</span>
                                @endif
                            </div>

                            <div class="product-info">
                                <a href="{{ route('front.singleProduct', $product->slug) }}" class="text-decoration-none">
                                <h6 class="product-brand">{{ $product->brand->name ?? 'Wooflix' }}</h6>
                                <p class="product-title">{{ $product->name }}</p>
                                </a>

                                <div class="product-price">
                                    <div>
                                        <span class="price">₹{{ number_format($product->sale_price, 2) }}</span>
                                        @if($product->regular_price > $product->sale_price)
                                            <span class="old-price">₹{{ number_format($product->regular_price) }}</span>
                                        @endif
                                    </div>
                                    <div class="product-action">
                                        <button class="add-btn">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No products found matching your criteria.</p>
                    </div>
                    @endforelse
                </div>

                @if($products->hasMorePages())
                    <div x-intersect="$wire.loadMore()" class="loading-state text-center py-5">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="mt-3">Loading products...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="filterDrawer">
        <div class="offcanvas-header">
            <h5>Filters</h5>
            <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <button wire:click="clearAll" class="clear-filters mb-3">Clear All</button>
            {{-- Brands for Mobile --}}
            <div class="filter-group mb-4">
                <h6>Brands</h6>
                @foreach($brands as $brand)
                <label class="filter-option d-block mb-2">
                    <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand->id }}" />
                    {{ $brand->name }}
                </label>
                @endforeach
            </div>
        </div>
    </div>
</div>