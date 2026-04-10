@extends('layouts.front')

@section('content')

@livewire('front.product-grid')

    {{-- <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
      <div class="container-xxl">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Shop</li>
        </ol>
      </div>
    </nav>

    <div class="shop-topbar">
      <div class="container-xxl">
        <div class="shop-topbar-inner">
          <h5 class="shop-category">All Products <span class="text-muted fs-6">({{ $products->total() }})</span></h5>
          <div class="sort-scroll">
            <button class="filter-btn d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">Filter</button>
            <button class="sort-btn active">Newest</button>
            <button class="sort-btn">Price: Low to High</button>
            <button class="sort-btn">Price: High to Low</button>
          </div>
        </div>
      </div>
    </div>

    <div class="container-xxl">
      <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
          <aside class="filter-sidebar">
            <div class="filter-header">
              <h5>Filters</h5>
              <button class="clear-filters">Clear All</button>
            </div>

            <div class="filter-group">
              <button class="filter-toggle">Brands <span class="arrow">⌄</span></button>
              <div class="filter-content">
                @foreach($brands as $brand)
                <label class="filter-option">
                  <input type="checkbox" name="brand[]" value="{{ $brand->id }}" />
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
                  <input type="checkbox" name="category[]" value="{{ $cat->id }}" />
                  <span class="checkmark"></span>
                  {{ $cat->name }} <span class="count">({{ $cat->products_count }})</span>
                </label>
                @endforeach
              </div>
            </div>
          </aside>
        </div>

        <div class="col-lg-9">
          <div class="row g-4">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->discount_percentage > 0)
                                <span class="product-badge">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                            
                            <a href="{{ route('front.singleProduct', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" />
                            </a>
                            
                            @if($product->rating)
                                <span class="product-rating">⭐ {{ $product->rating }}</span>
                            @endif
                        </div>

                        <div class="product-info">
                            <h6 class="product-brand">{{ $product->brand->name ?? 'Wooflix' }}</h6>
                            <p class="product-title">
                                <a href="{{ route('front.singleProduct', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 45) }}
                                </a>
                            </p>

                            <div class="product-price">
                                <div>
                                    <span class="price">₹{{ number_format($product->sale_price, 2) }}</span>
                                    @if($product->regular_price > $product->sale_price)
                                        <span class="old-price">₹{{ number_format($product->regular_price) }}</span>
                                    @endif
                                </div>
                                <div class="product-action">
                                    <button class="add-btn" data-id="{{ $product->id }}">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h4>No products found.</h4>
                </div>
            @endforelse
          </div>

          <div class="mt-5">
              {{ $products->links() }}
          </div>
        </div>
      </div>
    </div> --}}
@endsection