<div>
    {{-- 1. CATALOG TOPBAR & BREADCRUMBS (Only displays on full shop layout) --}}
    @if($mode === 'all')
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
                <div class="shop-topbar-inner bg-light px-lg-2">
                    <h5 class="shop-category">All Products</h5>

                    <div class="sort-scroll">
                        <button class="filter-btn" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
                            Filters
                        </button>

                        <button wire:click="$set('sort', 'best_seller')" class="sort-btn {{ $sort == 'best_seller' ? 'active' : '' }}">Best Seller</button>
                        <button wire:click="$set('sort', 'price_low')" class="sort-btn {{ $sort == 'price_low' ? 'active' : '' }}">Price: Low to High</button>
                        <button wire:click="$set('sort', 'price_high')" class="sort-btn {{ $sort == 'price_high' ? 'active' : '' }}">Price: High to Low</button>
                        <button wire:click="$set('sort', 'newest')" class="sort-btn {{ $sort == 'newest' ? 'active' : '' }}">Newest</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 2. GRID RENDERING MATRICES --}}
    <div class="container-xxl">
        <div class="row g-3 g-md-4" wire:loading.class="opacity-50">
            <div class="col-lg-12">

                @if($mode === 'featured')
                    <div 
                        class="deals-wrapper position-relative" 
                        x-data="{ 
                            scrollLeft() { $refs.slider.scrollBy({ left: -300, behavior: 'smooth' }) },
                            scrollRight() { $refs.slider.scrollBy({ left: 300, behavior: 'smooth' }) }
                        }"
                    >
                        <button class="deals-arrow left" @click="scrollLeft()">❮</button>

                        <div class="deals-scroll" x-ref="slider">
                            @forelse($products as $product)
                                @php
                                    if (!isset($selectedVariants[$product->id]) && $product->variants->isNotEmpty()) {
                                        $selectedVariants[$product->id] = $product->variants->first()->id;
                                    }
                                    $selectedId = $selectedVariants[$product->id] ?? null;
                                    $variant = $selectedId ? $product->variants->firstWhere('id', $selectedId) : null;
                                    $isOutOfStock = $variant ? ($variant->stock <= 0) : true;
                                    $displayPrice = $variant ? ($variant->sale_price ?? $variant->price) : $product->display_price;
                                    $basePrice = $variant ? $variant->price : $product->base_price;
                                    $discount = ($basePrice > 0 && $displayPrice < $basePrice) 
                                        ? round((($basePrice - $displayPrice) / $basePrice) * 100) 
                                        : 0;
                                    $isInCart = in_array((int)$selectedId, $cartVariantIds);
                                @endphp

                                <div class="product-card">
                                    <div class="product-image">
                                        @if($discount > 0)
                                            <span class="product-badge">{{ $discount }}% OFF</span>
                                        @elseif($product->discount_percentage)
                                            <span class="product-badge">{{ $product->discount_percentage }}% OFF</span>
                                        @endif

                                        <a href="{{ route('front.singleProduct', ['product_slug' => $product->slug, 'variant_slug' => $product->defaultVariant?->slug ?? 'default']) }}">
                                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
                                        </a>

                                        @if($product->rating)
                                            <span class="product-rating">⭐ {{ $product->rating }}</span>
                                        @endif
                                    </div>

                                    <div class="product-selection px-2 pt-2">
                                        @if($product->variants->count() > 1)
                                            <select class="form-select form-select-sm border-light-subtle shadow-none"
                                                    wire:model.live="selectedVariants.{{ $product->id }}"
                                                    style="font-size: 0.8rem; cursor: pointer;">
                                                @foreach($product->variants as $v)
                                                    <option value="{{ $v->id }}">
                                                        {{ $v->name }}
                                                        @if($v->price > $v->sale_price && $v->sale_price > 0)
                                                            ({{ round((($v->price - $v->sale_price) / $v->price) * 100) }}% Off)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div style="height: 31px;"></div>
                                        @endif
                                    </div>

                                    <div class="product-info border-bottom">
                                        <a href="{{ route('front.singleProduct', ['product_slug' => $product->slug, 'variant_slug' => $product->defaultVariant?->slug ?? 'default']) }}" class="text-decoration-none text-dark">
                                            <h6 class="product-brand">{{ $product->brand->name ?? 'Wooflix' }}</h6>
                                            <h5 class="product-title p" title="{{$product->name}}">
                                                {{ Str::limit($product->name, 70, '...') }}
                                            </h5>
                                        </a>

                                        <div class="product-price row d-flex align-items-center justify-content-between gap-1">
                                            <div class="col">
                                                <span class="price">₹{{ number_format($displayPrice, 2) }}</span>
                                                @if($discount > 0)
                                                    <div class="old-price text-muted text-decoration-line-through small ms-1">
                                                        ₹{{ number_format($basePrice, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="product-action col">
                                                <button class="add-btn w-100 {{ $isOutOfStock ? 'btn-light text-decoration-line-through' : ($isInCart ? 'btn-success' : '') }}" 
                                                        wire:click="addToCart({{ $product->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="addToCart({{ $product->id }})"
                                                        {{ ($isInCart || $isOutOfStock) ? 'disabled' : '' }}>
                                                    
                                                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                                        {{ $isOutOfStock ? 'Sold Out' : ($isInCart ? 'In Cart' : 'Add') }}
                                                    </span>
                                                    
                                                    <span wire:loading wire:target="addToCart({{ $product->id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="w-100 text-center py-4">
                                    <p class="text-muted">No featured deals available in this category tab.</p>
                                </div>
                            @endforelse
                        </div>

                        <button class="deals-arrow right" @click="scrollRight()">❯</button>
                    </div>

                @else
                    <div class="row g-4" wire:loading.class="opacity-50">
                        @forelse($products as $product)
                            @php
                                if (!isset($selectedVariants[$product->id]) && $product->variants->isNotEmpty()) {
                                    $selectedVariants[$product->id] = $product->variants->first()->id;
                                }
                                $selectedId = $selectedVariants[$product->id] ?? null;
                                $variant = $selectedId ? $product->variants->firstWhere('id', $selectedId) : null;
                                $isOutOfStock = $variant ? ($variant->stock <= 0) : true;
                                $displayPrice = $variant ? ($variant->sale_price ?? $variant->price) : $product->display_price;
                                $basePrice = $variant ? $variant->price : $product->base_price;
                                $discount = ($basePrice > 0 && $displayPrice < $basePrice) 
                                    ? round((($basePrice - $displayPrice) / $basePrice) * 100) 
                                    : 0;
                                $isInCart = in_array((int)$selectedId, $cartVariantIds);
                            @endphp

                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="product-card h-100 d-flex flex-column justify-content-between rounded bg-white overflow-hidden">
                                    <div class="product-image">
                                        @if($discount > 0)
                                            <span class="product-badge">{{ $discount }}% OFF</span>
                                        @elseif($product->discount_percentage)
                                            <span class="product-badge">{{ $product->discount_percentage }}% OFF</span>
                                        @endif

                                        <a href="{{ route('front.singleProduct', ['product_slug' => $product->slug, 'variant_slug' => $product->defaultVariant?->slug ?? 'default']) }}">
                                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
                                        </a>

                                        @if($product->rating)
                                            <span class="product-rating">⭐ {{ $product->rating }}</span>
                                        @endif
                                    </div>

                                    <div class="product-selection px-2 pt-2">
                                        @if($product->variants->count() > 1)
                                            <select class="form-select form-select-sm border-light-subtle shadow-none"
                                                    wire:model.live="selectedVariants.{{ $product->id }}"
                                                    style="font-size: 0.8rem; cursor: pointer;">
                                                @foreach($product->variants as $v)
                                                    <option value="{{ $v->id }}">
                                                        {{ $v->name }}
                                                        @if($v->price > $v->sale_price && $v->sale_price > 0)
                                                            ({{ round((($v->price - $v->sale_price) / $v->price) * 100) }}% Off)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div style="height: 31px;"></div>
                                        @endif
                                    </div>

                                    <div class="product-info border-bottom">
                                        <a href="{{ route('front.singleProduct', ['product_slug' => $product->slug, 'variant_slug' => $product->defaultVariant?->slug ?? 'default']) }}" class="text-decoration-none text-dark">
                                            <h6 class="product-brand">{{ $product->brand->name ?? 'Wooflix' }}</h6>
                                            <h5 class="product-title p" title="{{$product->name}}">
                                                {{ Str::limit($product->name, 70, '...') }}
                                            </h5>
                                        </a>

                                        <div class="product-price row d-flex align-items-center justify-content-between gap-1">
                                            <div class="col">
                                                <span class="price">₹{{ number_format($displayPrice, 2) }}</span>
                                                @if($discount > 0)
                                                    <div class="old-price text-muted text-decoration-line-through small ms-1">
                                                        ₹{{ number_format($basePrice, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="product-action col">
                                                <button class="add-btn w-100 {{ $isOutOfStock ? 'btn-light text-decoration-line-through' : ($isInCart ? 'btn-success' : '') }}" 
                                                        wire:click="addToCart({{ $product->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="addToCart({{ $product->id }})"
                                                        {{ ($isInCart || $isOutOfStock) ? 'disabled' : '' }}>
                                                    
                                                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                                        {{ $isOutOfStock ? 'Sold Out' : ($isInCart ? 'In Cart' : 'Add') }}
                                                    </span>
                                                    
                                                    <span wire:loading wire:target="addToCart({{ $product->id }})">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    </span>
                                                </button>
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

                    {{-- Infinite Scroll Observer Sentinel (Only triggers during full directory exploration) --}}
                    @if($mode === 'all' && $products->hasMorePages())
                        <div 
                            x-data 
                            x-intersect.margin.200px="$wire.loadMore()" 
                            class="text-center my-5 py-4"
                        >
                            <div class="spinner-border text-orange" role="status">
                                <span class="visually-hidden">Loading more products...</span>
                            </div>
                            <p class="text-muted small mt-2">Loading more products...</p>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>

    {{-- 3. SIDEBAR OFFCANVAS INTERFACE SLIDER (Global Shop Mode Only) --}}
    @if($mode === 'all')
        <div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="filterDrawer">
            <div class="offcanvas-header">
                <h5>Filters</h5>
                <button class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body">
                <div class="filter-sidebar">
                    <div class="filter-group">
                        <button class="filter-toggle">Brands <span class="arrow">⌄</span></button>
                        <div class="filter-content">
                            @foreach($brands as $brand)
                                <label class="filter-option">
                                    <input 
                                        type="checkbox"
                                        wire:click="toggleBrand('{{ $brand->slug }}')"
                                        @checked(in_array($brand->slug, $selectedBrands ?? []))
                                    />
                                    <span class="checkmark"></span>
                                    {{ $brand->name }} 
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group">
                        <button class="filter-toggle">Category <span class="arrow">⌄</span></button>
                        <div class="filter-content">
                            @foreach($categories as $cat)
                                <label class="filter-option">
                                    <input 
                                        type="checkbox"
                                        wire:click="toggleCategory('{{ $cat->slug }}')"
                                        @checked(in_array($cat->slug, $selectedCategories ?? []))
                                    />
                                    <span class="checkmark"></span>
                                    {{ $cat->name }} 
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @foreach($filterGroups as $type => $tags)
                        <div class="filter-group">
                            <button class="filter-toggle">
                                {{ ucfirst(str_replace('_', ' ', $type)) }} <span class="arrow">⌄</span>
                            </button>
                            <div class="filter-content">
                                @foreach($tags as $tag)
                                    <label class="filter-option">
                                        <input 
                                            type="checkbox"
                                            wire:click="toggleTag('{{ $tag->slug }}')"
                                            @checked(in_array($tag->slug, $selectedTags))
                                        />
                                        <span class="checkmark"></span>
                                        {{ $tag->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>