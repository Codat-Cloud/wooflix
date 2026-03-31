@extends('layouts.front')

@section('content')

  <!-- Hero Section -->
  <section class="hero-banner">
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">

        <div class="carousel-inner">

            @foreach($banners as $key => $banner)

                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    <a href="{{ $banner->link ?? '#' }}">

                        <picture>

                            @if($banner->mobile_image)
                                <source
                                    media="(max-width: 768px)"
                                    srcset="{{ asset('storage/' . $banner->mobile_image) }}"
                                />
                            @endif

                            <img
                                src="{{ asset('storage/' . $banner->desktop_image) }}"
                                class="d-block w-100"
                                alt="{{ $banner->title ?? 'Banner' }}"
                            />

                        </picture>

                    </a>
                </div>

            @endforeach

        </div>

        {{-- Arrows --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
  </section>

  <!-- Offer Cards -->
  <section class="offer-section">
      <div class="container-xxl">
          <div class="offer-scroll d-flex flex-nowrap flex-lg-wrap">

              @foreach($offers as $offer)
                  <a href="{{ $offer->link ?? '#' }}" class="offer-item">
                      <img 
                          src="{{ asset('storage/' . $offer->image) }}" 
                          alt="{{ $offer->title ?? 'Offer' }}"
                      />
                  </a>
              @endforeach

          </div>
      </div>
  </section>

  {{-- Cards --}}
  @foreach($sections as $section)

    <section class="mt-5">
        <div class="container-xxl">

            <h2 class="fw-bold">{{ $section->title }}</h2>
            <p class="section-subtitle">{{ $section->subtitle }}</p>

            @php
                $gridMap = [
                    'grid_4' => 'col-6 col-md-6 col-lg-3', // 4 per row
                    'grid_6' => 'col-6 col-md-4 col-lg-2', // 6 per row
                    'grid_8' => 'col-6 col-md-3 col-lg-2', // approx 6–8 responsive
                ];

                $gridClass = $gridMap[$section->layout] ?? 'col-6 col-md-4 col-lg-3';
            @endphp

            {{-- ================= CATEGORY SCROLL ================= --}}
            @if($section->layout === 'scroll')

                <div class="category-scroll">

                    @foreach($section->items as $item)

                        @php
                            if ($section->type === 'category') {
                                $data = $categories[$item->item_id] ?? null;
                            } elseif ($section->type === 'brand') {
                                $data = $brands[$item->item_id] ?? null;
                            } else {
                                $data = $products[$item->item_id] ?? null;
                            }
                        @endphp

                        @if($data)

                            <a href="#" class="category-item">

                                <div class="category-card shadow">
                                    <img 
                                        src="{{ asset('storage/' . ($item->image ?? $data->image)) }}" 
                                        alt="{{ $item->title ?? $data->name }}"
                                        loading="lazy"
                                    />
                                </div>

                                <div class="category-title">
                                    {{ $item->title ?? $data->name }}
                                </div>

                            </a>

                        @endif

                    @endforeach

                </div>

            @endif


            {{-- ================= BRAND GRID ================= --}}
            @if(str_starts_with($section->layout, 'grid') && $section->type === 'brand')

                <div class="row g-4">

                    @foreach($section->items as $item)

                        @php $brand = $brands[$item->item_id] ?? null; @endphp

                        @if($brand)

                            <div class="{{ $gridClass }}">
                                <a href="#" class="brand-card text-decoration-none">

                                  <img 
                                      src="{{ asset('storage/' . ($item->image ?? $brand->image)) }}" 
                                      class="w-100"
                                      loading="lazy"
                                  />

                                  <div class="text-center brand-card-text">
                                      {{ $item->title ?? $brand->name }}
                                  </div>

                                </a>
                            </div>

                        @endif

                    @endforeach

                </div>

            @endif


            {{-- ================= PRODUCT GRID ================= --}}
            @if(str_starts_with($section->layout, 'grid') && $section->type === 'product')

                <div class="row g-4">

                    @foreach($section->items as $item)

                        @php $product = $products[$item->item_id] ?? null; @endphp

                        @if($product)

                            <div class="{{ $gridClass }}">

                                <div class="product-card">

                                    <div class="product-image">
                                        <span class="product-badge">10% OFF</span>

                                        <img 
                                            src="{{ asset('storage/' . $product->main_image) }}" 
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        />

                                        <span class="product-rating">⭐ 5.0</span>
                                    </div>

                                    <div class="product-info">

                                        <h6 class="product-brand">
                                            {{ $product->brand->name ?? '' }}
                                        </h6>

                                        <p class="product-title">
                                            {{ $product->name }}
                                        </p>

                                        <div class="product-price">
                                            <div>
                                                <span class="price">₹{{ $product->base_price }}</span>
                                            </div>

                                            <div class="product-action">
                                                <button class="add-btn">Add</button>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @endforeach

                </div>

            @endif

            {{-- ================= Tabbed Category Products ================== --}}
            @if($section->type === 'tabbed_category_products')

              <section class="deals-section">

                <div class="container-xxl">
                  <h2>{{ $section->title }}</h2>
                  <p class="deals-subtitle">{{ $section->subtitle }}</p>

                  {{-- Tabs --}}
                  <ul class="nav deals-tabs">

                    @foreach($section->items as $index => $item)

                      <li class="nav-item">
                        <button
                          class="nav-link {{ $index === 0 ? 'active' : '' }}"
                          data-bs-toggle="tab"
                          data-bs-target="#tab-{{ $item->id }}"
                        >
                          {{ $item->title ?? $categories[$item->item_id]->name ?? '' }}
                        </button>
                      </li>

                    @endforeach

                  </ul>
                </div>

                {{-- Tab Content --}}
                <div class="tab-content">

                  @foreach($section->items as $index => $item)

                    <div
                      class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                      id="tab-{{ $item->id }}"
                    >

                      <div class="deals-wrapper">
                        <div class="deals-scroll">

                          @foreach($item->products as $product)

                            <div class="product-card">
                              <div class="product-image">
                                <img src="{{ asset('storage/'.$product->main_image) }}">
                              </div>

                              <div class="product-info">
                                <h6>{{ $product->brand->name ?? '' }}</h6>
                                <p>{{ $product->name }}</p>
                                <span class="price">₹{{ $product->base_price }}</span>
                              </div>
                            </div>

                          @endforeach

                        </div>
                      </div>

                    </div>

                  @endforeach

                </div>

              </section>

            @endif

        </div>
    </section>

  @endforeach

    <!-- categories -->
    <section class="categories">
      <div class="container-xxl">
        <h2 class="fw-bold">Try Yum Nums</h2>
        <p class="category-subtitle">Soft Chews: For ₹199</p>

        <div class="category-scroll">
          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/p1.avif')}}" alt="Shop Now" />
            </div>

            <div class="category-title">Shop Now</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/p2.avif')}}" alt="Banana & Real Chicken" />
            </div>

            <div class="category-title">Banana & Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/p3.avif')}}" alt="Blueberry & Real Chicken" />
            </div>

            <div class="category-title">Blueberry & Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/p4.avif')}}" alt="Carrot & Real Chicken" />
            </div>

            <div class="category-title">Carrot & Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/p5.avif')}}" alt="Real Chicken" />
            </div>

            <div class="category-title">Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/p6.avif')}}" alt="Pumpkin & Real Chicken" />
            </div>

            <div class="category-title">Pumpkin & Real Chicken</div>
          </a>
        </div>
      </div>
    </section>

    <!-- categories -->
    <section class="categories">
      <div class="container-xxl">
        <h2 class="fw-bold">Sara’s Wholesome Food by HUFT</h2>
        <p class="category-subtitle">Expert Nutrition starting at ₹99</p>

        <div class="category-scroll">
          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/c9.avif')}}" alt="Shop Now" />
            </div>

            <div class="category-title">Shop Now</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/c14.webp')}}" alt="Banana & Real Chicken" />
            </div>

            <div class="category-title">Banana & Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img
                src="{{ asset('/images/c13.webp')}}"
                alt="Blueberry & Real Chicken"
              />
            </div>

            <div class="category-title">Blueberry & Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/c12.avif')}}" alt="Carrot & Real Chicken" />
            </div>

            <div class="category-title">Carrot & Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/c11.avif')}}" alt="Real Chicken" />
            </div>

            <div class="category-title">Real Chicken</div>
          </a>

          <a href="#" class="category-item">
            <div class="category-card shadow">
              <img src="{{ asset('/images/c10.avif')}}" alt="Pumpkin & Real Chicken" />
            </div>

            <div class="category-title">Pumpkin & Real Chicken</div>
          </a>
        </div>
      </div>
    </section>

    <!-- Brand  -->
    <section class="brand-section mt-5">
      <div class="container-xxl">
        <h2 class="fw-bold">Global Brands, Great Deals!</h2>
        <p class="section-subtitle">Around the world with HUFT</p>

        <div class="row g-4">
          <div class="col-6 col-md-6 col-lg-2">
            <a href="#" class="brand-card text-decoration-none">
              <img
                src="{{ asset('/images/b1.avif')}}"
                class="w-100"
                alt="Complete Wet Meals"
              />

              <div class="text-center brand-card-text">Code</div>
            </a>
          </div>

          <div class="col-6 col-md-6 col-lg-2">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/b2.avif')}}" class="w-100" alt="Broth" />

              <div class="text-center brand-card-text">
                Broth | ₹129 onwards
              </div>
            </a>
          </div>

          <div class="col-6 col-md-6 col-lg-2">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/b3.avif')}}" class="w-100" alt="Mousse" />

              <div class="text-center brand-card-text">
                Mousse | ₹129 onwards
              </div>
            </a>
          </div>

          <div class="col-6 col-md-6 col-lg-2">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/b4.avif')}}" class="w-100" alt="Pate" />

              <div class="text-center brand-card-text">Pâté | ₹129 onwards</div>
            </a>
          </div>
          <div class="col-6 col-md-6 col-lg-2">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/b5.avif')}}" class="w-100" alt="Pate" />

              <div class="text-center brand-card-text">Pâté | ₹129 onwards</div>
            </a>
          </div>
          <div class="col-6 col-md-6 col-lg-2">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/b6.avif')}}" class="w-100" alt="Pate" />

              <div class="text-center brand-card-text">Pâté | ₹129 onwards</div>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Category  -->
    <section class="brand-section mt-5">
      <div class="container-xxl">
        <h2 class="fw-bold">Changing-Season Essentials</h2>
        <p class="section-subtitle">Not winter, not summer… just perfect!</p>

        <div class="row g-4">
          <div class="col-6 col-md-6 col-lg-3">
            <a href="#" class="brand-card text-decoration-none">
              <img
                src="{{ asset('/images/s1.webp')}}"
                class="w-100"
                alt="Complete Wet Meals"
              />

              <div class="text-center brand-card-text">Code</div>
            </a>
          </div>

          <div class="col-6 col-md-6 col-lg-3">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/s2.webp')}}" class="w-100" alt="Broth" />

              <div class="text-center brand-card-text">
                Broth | ₹129 onwards
              </div>
            </a>
          </div>

          <div class="col-6 col-md-6 col-lg-3">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/s3.webp')}}" class="w-100" alt="Mousse" />

              <div class="text-center brand-card-text">
                Mousse | ₹129 onwards
              </div>
            </a>
          </div>

          <div class="col-6 col-md-6 col-lg-3">
            <a href="#" class="brand-card text-decoration-none">
              <img src="{{ asset('/images/s4.webp')}}" class="w-100" alt="Pate" />

              <div class="text-center brand-card-text">Pâté | ₹129 onwards</div>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Product Slider -->
    <section class="deals-section">
      <div class="container-xxl">
        <h2>Deals to Buy for!</h2>
        <p class="deals-subtitle">These should’ve been in your cart already</p>

        <!-- Category Tabs -->
        <ul class="nav deals-tabs" role="tablist">
          <li class="nav-item">
            <button
              class="nav-link active"
              data-category="cat-toys"
              data-bs-toggle="tab"
              data-bs-target="#cat-toys"
            >
              🧶 Cat Toys
            </button>
          </li>
          <li class="nav-item">
            <button
              class="nav-link"
              data-category="toilet-trays"
              data-bs-toggle="tab"
              data-bs-target="#toilet-trays"
            >
              🚽 Toilet Trays
            </button>
          </li>
          <li class="nav-item">
            <button
              class="nav-link"
              data-category="cat-treats"
              data-bs-toggle="tab"
              data-bs-target="#cat-treats"
            >
              🍡 Cat Treats
            </button>
          </li>
          <li class="nav-item">
            <button
              class="nav-link"
              data-category="cat-beds"
              data-bs-toggle="tab"
              data-bs-target="#cat-beds"
            >
              🐈 Cat Beds
            </button>
          </li>
        </ul>
      </div>

      <!-- Tab Panels -->
      <div class="tab-content">
        <!-- Initial category rendered immediately -->
        <div class="tab-pane fade show active" id="cat-toys">
          <div class="deals-wrapper">
            <button class="deals-arrow left">❮</button>

            <div class="deals-scroll">
              <!-- Example product card -->
              <div class="product-card">
                <div class="product-image">
                  <span class="product-badge">10% OFF</span>

                  <img src="{{ asset('/images/p1.webp')}}" alt="Product" />

                  <span class="product-rating">⭐ 5.0</span>
                </div>

                <div class="product-info">
                  <h6 class="product-brand">Heads Up For Tails</h6>

                  <p class="product-title">
                    HUFT Lady Buggs Plush With Catnip Toy
                  </p>

                  <div class="product-price">
                    <div>
                      <span class="price">₹174.30</span>

                      <span class="old-price">₹249</span>

                      <span class="discount">(30%)</span>
                    </div>

                    <div class="product-action">
                      <button class="add-btn">Add</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Example product card -->
              <div class="product-card">
                <div class="product-image">
                  <span class="product-badge">10% OFF</span>

                  <img src="{{ asset('/images/p2.webp')}}" alt="Product" />

                  <span class="product-rating">⭐ 5.0</span>
                </div>

                <div class="product-info">
                  <h6 class="product-brand">Heads Up For Tails</h6>

                  <p class="product-title">
                    HUFT Lady Buggs Plush With Catnip Toy
                  </p>

                  <div class="product-price">
                    <span class="price">₹174.30</span>

                    <span class="old-price">₹249</span>

                    <span class="discount">(30%)</span>
                    <div class="product-action">
                      <button class="add-btn">Add</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Example product card -->
              <div class="product-card">
                <div class="product-image">
                  <span class="product-badge">10% OFF</span>

                  <img src="{{ asset('/images/p3.webp')}}" alt="Product" />

                  <span class="product-rating">⭐ 5.0</span>
                </div>

                <div class="product-info">
                  <h6 class="product-brand">Heads Up For Tails</h6>

                  <p class="product-title">
                    HUFT Lady Buggs Plush With Catnip Toy
                  </p>

                  <div class="product-price">
                    <span class="price">₹174.30</span>

                    <span class="old-price">₹249</span>

                    <span class="discount">(30%)</span>
                    <div class="product-action">
                      <button class="add-btn">Add</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Example product card -->
              <div class="product-card">
                <div class="product-image">
                  <span class="product-badge">10% OFF</span>

                  <img src="{{ asset('/images/p4.webp')}}" alt="Product" />

                  <span class="product-rating">⭐ 5.0</span>
                </div>

                <div class="product-info">
                  <h6 class="product-brand">Heads Up For Tails</h6>

                  <p class="product-title">
                    HUFT Lady Buggs Plush With Catnip Toy
                  </p>

                  <div class="product-price">
                    <span class="price">₹174.30</span>

                    <span class="old-price">₹249</span>

                    <span class="discount">(30%)</span>
                    <div class="product-action">
                      <button class="add-btn">Add</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Example product card -->
              <div class="product-card">
                <div class="product-image">
                  <!-- <span class="product-badge">10% OFF</span> -->

                  <img src="{{ asset('/images/p5.webp')}}" alt="Product" />

                  <span class="product-rating">⭐ 5.0</span>
                </div>

                <div class="product-info">
                  <h6 class="product-brand">Heads Up For Tails</h6>

                  <p class="product-title">
                    HUFT Lady Buggs Plush With Catnip Toy
                  </p>

                  <div class="product-price">
                    <span class="price">₹174.30</span>

                    <span class="old-price">₹249</span>

                    <span class="discount">(30%)</span>
                    <div class="product-action">
                      <button class="add-btn">Add</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <button class="deals-arrow right">❯</button>
          </div>
        </div>

        <!-- Other tabs load later -->
        <div
          class="tab-pane fade"
          id="toilet-trays"
          data-endpoint="/api/deals/toilet-trays"
        ></div>
        <div
          class="tab-pane fade"
          id="cat-treats"
          data-endpoint="/api/deals/cat-treats"
        ></div>
        <div
          class="tab-pane fade"
          id="cat-beds"
          data-endpoint="/api/deals/cat-beds"
        ></div>
      </div>
      
    </section>

    <!-- Single Category Banner -->
    <section class="promo-section">
      <div class="">
        <div class="row g-0 promo-wrapper">
          <div class="col-lg-6">
            <div class="promo-image">
              <img src="{{ asset('/images/promo.jpg')}}" alt="Pet With Toy" />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="promo-content">
              <h3>
                GET UP TO 25% OFF ON ALL MEDICINES<br />
                FOR YOUR PET WITH WOOFLIX PHARMACY
              </h3>

              <p>
                VACCINATIONS | DEWORMING | AND MANY MORE<br />
                HEALTH SUPPLEMENTS ARE ALSO AVAILABLE
              </p>

              <a href="#" class="promo-btn"> SHOP NOW </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Blog Section -->

    <section class="blog-section">
      <div class="container-xxl">
        <h2 class="section-title">Explore the World of HUFT</h2>

        <p class="section-subtitle">
          Bringing joy to pets and families, one home at a time!
        </p>

        <div class="row g-4">
          <div class="col-6 col-md-3">
            <a href="/blog/post-1" class="blog-card">
              <div class="blog-image">
                <img
                  src="{{ asset('/images/b1.webp')}}"
                  class="img-fluid"
                  alt="HUFTians react"
                />
              </div>

              <p class="blog-title">HUFTians react to your pets</p>
            </a>
          </div>

          <div class="col-6 col-md-3">
            <a href="/blog/post-2" class="blog-card">
              <div class="blog-image">
                <img
                  src="{{ asset('/images/b2.webp')}}"
                  class="img-fluid"
                  alt="Shopping approved"
                />
              </div>

              <p class="blog-title">
                Every shopping has to be approved by them!
              </p>
            </a>
          </div>

          <div class="col-6 col-md-3">
            <a href="/blog/post-3" class="blog-card">
              <div class="blog-image">
                <img
                  src="{{ asset('/images/b3.webp')}}"
                  class="img-fluid"
                  alt="Dog feelings"
                />
              </div>

              <p class="blog-title">Things dogs wish we knew</p>
            </a>
          </div>

          <div class="col-6 col-md-3">
            <a href="/blog/post-4" class="blog-card">
              <div class="blog-image">
                <img
                  src="{{ asset('/images/b4.webp')}}"
                  class="img-fluid"
                  alt="Groomers"
                />
              </div>

              <p class="blog-title">Our groomers go the extra mile</p>
            </a>
          </div>
        </div>
      </div>
    </section>


@endsection