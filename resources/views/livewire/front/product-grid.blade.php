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
                    {{-- <div class="filter-header">
                        <h5>Filters</h5>
                        <button wire:click="clearAll" class="clear-filters">Clear All</button>
                    </div> --}}

                    {{-- Separate checking for Brands and Categories --}}
                       {{-- @if(count($this->selectedBrands) > 0 || count($this->selectedCategories) > 0)
                            <div class="active-filters">
                                @foreach($this->selectedBrands as $slug)
                                    <span class="filter-chip" wire:click="toggleBrand('{{ $slug }}')">
                                        {{ Str::headline($slug) }} ✕
                                    </span>
                                @endforeach
                            </div>
                        @endif --}}

                    <div class="filter-group">
                        <button class="filter-toggle">Brands <span class="arrow">⌄</span></button>
                        <div class="filter-content">
                            @foreach($brands as $brand)
                            <label class="filter-option">
                                {{-- We now bind the 'slug' to the model --}}
                                <input 
                                    type="checkbox"
                                    wire:click="toggleBrand('{{ $brand->slug }}')"
                                    @checked(in_array($brand->slug, $selectedBrands ?? []))
                                />
                                <span class="checkmark"></span>
                                {{ $brand->name }} 
                                {{-- <span class="count">({{ $brand->products_count }})</span> --}}
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
                                {{-- <span class="count">({{ $cat->products_count }})</span> --}}
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
                                    @if(in_array($tag->slug, $this->selectedTags)) checked @endif
                                    {{-- @checked(in_array($tag->slug, $selectedTags)) --}}
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

            <div class="col-lg-9">
                <div class="row g-4" wire:loading.class="opacity-50">
                @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card h-100">
                            @php
                                // Pre-select the first variant ID if not already selected by the user
                                if (!isset($selectedVariants[$product->id]) && $product->variants->isNotEmpty()) {
                                    $selectedVariants[$product->id] = $product->variants->first()->id;
                                }

                                // Identify current data based on selection
                                $selectedId = $selectedVariants[$product->id] ?? null;
                                $variant = $selectedId ? $product->variants->firstWhere('id', $selectedId) : null;
                                
                                // Display logic: Variant price first, then product price
                                $displayPrice = $variant ? ($variant->sale_price ?? $variant->price) : $product->display_price;
                                $basePrice = $variant ? $variant->price : $product->base_price;
                                $discount = ($basePrice > 0 && $displayPrice < $basePrice) 
                                            ? round((($basePrice - $displayPrice) / $basePrice) * 100) 
                                            : 0;
                            @endphp

                            <div class="product-image">
                                @if($discount > 0)
                                    <span class="product-badge">{{ $discount }}% OFF</span>
                                @elseif($product->discount_percentage)
                                    <span class="product-badge">{{ $product->discount_percentage }}% OFF</span>
                                @endif

                                <a href="{{ route('front.singleProduct', ['product_slug' => $product->slug, 'variant_slug' => $product->defaultVariant->slug]) }}">
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
                                </a>

                                @if($product->rating)
                                    <span class="product-rating">⭐ {{ $product->rating }}</span>
                                @endif
                            </div>

                            {{-- Dropdown Section: Variation selection --}}
                            <div class="product-selection px-2 pt-2">
                                @if($product->variants->isNotEmpty())
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
                                    <div style="height: 31px;"></div> {{-- Maintains height alignment --}}
                                @endif
                            </div>

                            <div class="product-info border-bottom">
                                <a href="{{ route('front.singleProduct', ['product_slug' => $product->slug, 'variant_slug' => $product->defaultVariant->slug]) }}" class="text-decoration-none text-dark">
                                    {{-- {{ dd($product->defaultVariant) }} --}}
                                    <h6 class="product-brand">{{ $product->brand->name ?? 'Wooflix' }}</h6>
                                    <h5 class="product-title p" title="{{$product->name}}">
                                        {{ Str::limit($product->name, 70, '...') }}
                                    </h5>
                                </a>

                                <div class="product-price">
                                    <div>
                                        <span class="price">₹{{ number_format($displayPrice, 2) }}</span>
                                        @if($discount > 0)
                                            <div class="old-price text-muted text-decoration-line-through small ms-1">
                                                ₹{{ number_format($basePrice, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="product-action">
                                        @php
                                            $isInCart = in_array((int)$selectedId, $cartVariantIds);
                                        @endphp

                                        <button class="add-btn {{ $isInCart ? 'btn-success' : '' }}" 
                                                wire:click="addToCart({{ $product->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="addToCart({{ $product->id }})"
                                                {{ $isInCart ? 'disabled' : '' }}>
                                            
                                            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                                {{ $isInCart ? 'In Cart' : 'Add' }}
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
        </div>
    </div>

    <div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="filterDrawer">
        <div class="offcanvas-header">
            <h5>Filters</h5>
            <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            {{-- <button wire:click="clearAll" class="clear-filters mb-3">Clear All</button> --}}
            {{-- Brands for Mobile --}}
<div class="filter-group mb-4">
    <h6>Brands</h6>
    @foreach($brands as $brand)
    <label class="filter-option d-block mb-2">
        <input 
            type="checkbox" 
            wire:click="toggleBrand('{{ $brand->slug }}')"
            @checked(in_array($brand->slug, $selectedBrands ?? []))
        />
        {{ $brand->name }}
    </label>
    @endforeach
</div>

<div class="filter-group mb-4">
    <h6>Categories</h6>
    @foreach($categories as $cat)
    <label class="filter-option d-block mb-2">
        <input 
            type="checkbox" 
            wire:click="toggleCategory('{{ $cat->slug }}')"
            @checked(in_array($cat->slug, $selectedCategories ?? []))
        />
        {{ $cat->name }}
    </label>
    @endforeach
</div>

@foreach($filterGroups as $type => $tags)
<div class="filter-group mb-4">
    <h6>{{ ucfirst(str_replace('_', ' ', $type)) }}</h6>
    @foreach($tags as $tag)
    <label class="filter-option d-block mb-2">
        <input 
            type="checkbox"
            wire:click="toggleTag('{{ $tag->slug }}')"
            @checked(in_array($tag->slug, $this->selectedTags))
        />
        {{ $tag->name }}
    </label>
    @endforeach
</div>
@endforeach
        </div>
    </div>
</div>