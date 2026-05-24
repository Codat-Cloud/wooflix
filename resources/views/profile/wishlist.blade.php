@extends('layouts.front')

@section('content')

    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
      <div class="container-xxl">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/">Home</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">My Wishlist</li>
        </ol>
      </div>
    </nav>

    <div class="container-xxl py-4">
        <h4 class="fw-bold mb-4">❤️ Your Paw-List ({{ $wishlist->total() }} items)</h4>
        
        <div class="row g-4">
            @forelse($wishlist as $item)
                @php
                    $product = $item->product;
                    $variant = $item->variant;

                    // Skip row iteration if the parent product was hard deleted from database
                    if (!$product) continue;

                    // If a specific variant was saved, use its properties, otherwise fall back to base product
                    $displayName = $variant ? $product->name . ' (' . $variant->name . ')' : $product->name;
                    $displayPrice = $variant ? $variant->display_price : $product->display_price;
                    $basePrice = $variant ? $variant->price : $product->base_price;
                    $isOutOfStock = $variant ? !$variant->in_stock : false;

                    $discount = ($basePrice > 0 && $displayPrice < $basePrice) 
                                ? round((($basePrice - $displayPrice) / $basePrice) * 100) 
                                : 0;
                    
                    // Route params mapping logic
                    $routeParams = [
                        'product_slug' => $product->slug, 
                        'variant_slug' => $variant ? $variant->slug : ($product->defaultVariant->slug ?? 'default')
                    ];
                @endphp

                <div class="col-6 col-md-4 col-lg-3" id="wishlist-row-{{ $item->id }}">
                    <div class="product-card h-100 position-relative shadow-sm border-0 bg-white rounded p-2">
                        
                        <div class="product-image position-relative text-center overflow-hidden bg-light rounded" style="height: 200px;">
                            @if($discount > 0)
                                <span class="product-badge position-absolute top-0 start-0 m-2 bg-orange text-white px-2 py-1 small rounded fw-bold" style="font-size: 0.75rem; z-index: 2;">
                                    {{ $discount }}% OFF
                                </span>
                            @endif

                            @if($isOutOfStock)
                                <span class="position-absolute top-0 end-0 m-2 bg-danger text-white px-2 py-1 small rounded fw-bold" style="font-size: 0.70rem; z-index: 2; text-transform: uppercase;">
                                    Sold Out
                                </span>
                            @endif

                            <a href="{{ route('front.singleProduct', $routeParams) }}" class="d-flex align-items-center justify-content-center h-100">
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $displayName }}" 
                                     class="img-fluid" 
                                     style="max-height: 180px; object-fit: contain; {{ $isOutOfStock ? 'filter: grayscale(1); opacity: 0.6;' : '' }}" />
                            </a>
                        </div>

                        <div class="product-info pt-3 px-1">
                            <h5 class="product-title fw-bold text-dark mb-1 text-truncate" title="{{ $displayName }}" style="font-size: 0.95rem;">
                                {{ Str::limit($displayName, 50, '...') }}
                            </h5>
                            
                            <div class="product-price d-flex align-items-center gap-2 my-2">
                                <span class="price fw-bold text-dark">₹{{ number_format($displayPrice, 2) }}</span>
                                @if($discount > 0)
                                    <span class="old-price text-muted text-decoration-line-through small">₹{{ number_format($basePrice, 2) }}</span>
                                @endif
                            </div>

                            <div class="d-flex gap-2 mt-3">

                                <a href="{{ route('front.singleProduct', $routeParams) }}" 
                                   class="btn btn-orange flex-grow-1 text-white fw-bold py-2 text-center text-decoration-none">
                                    View Options
                                </a>
                                
                                {{-- Direct Hook reference for your Livewire item removal --}}
                                <form action="{{ route('front.wishlistRemove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm py-2 px-3" title="Remove from Wishlist">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 bg-light rounded shadow-sm border">
                    <div class="fs-1 mb-2">💔</div>
                    <h5 class="fw-bold text-secondary">Your wishlist is lonely!</h5>
                    <p class="text-muted small">Explore our collections to add items your furry friend will love.</p>
                    <a href="{{ route('front.shop') }}" class="btn btn-orange text-white fw-bold mt-2 px-4">Shop Now</a>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $wishlist->links() }}
        </div>
    </div>

@endsection