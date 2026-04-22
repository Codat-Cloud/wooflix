@extends('layouts.front')

@section('content')

  {{-- Hero Section --}}
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

  {{-- Offer Cards --}}
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

  {{-- Page Sections --}}
  @foreach($sections as $section)
      {{-- 1. Determine Layout Mode --}}
      @php
          $isScroll = $section->layout === 'scroll';
          $isTabbed = $section->type === 'tabbed_category_products';
          
          $gridMap = [
              'grid_4' => 'col-6 col-md-4 col-lg-3',
              'grid_6' => 'col-6 col-md-4 col-lg-2',
              'grid_8' => 'col-6 col-md-3 col-lg-1-5', // Custom 8-col logic
          ];
          $gridClass = $gridMap[$section->layout] ?? 'col-6 col-md-4 col-lg-3';
      @endphp

      {{-- 2. TABBED DEALS LAYOUT (Special Product Slider) --}}
      @if($isTabbed)
        <section class="deals-section mt-5">
          <div class="container-xxl">
              <h2 class="fw-bold">{{ $section->title }}</h2>
              <p class="deals-subtitle">{{ $section->subtitle }}</p>

              <ul class="nav deals-tabs" role="tablist">
                  @foreach($section->items as $index => $item)
                      <li class="nav-item">
                          <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                  data-bs-toggle="tab" 
                                  data-bs-target="#tab-{{ $section->id }}-{{ $index }}">
                              {!! $item->title !!}
                          </button>
                      </li>
                  @endforeach
              </ul>
          </div>

          <div class="tab-content">
              @foreach($section->items as $index => $item)
                  <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="tab-{{ $section->id }}-{{ $index }}">
                    <div class="container-xxl">

                      <div class="deals-wrapper">
                          <button class="deals-arrow left">❮</button>
                          
                          <div class="deals-scroll">
                              {{-- This is where the manually selected 'is_featured' products from the controller appear --}}
                              @foreach($item->products as $p)
                                  <div class="product-card">
                                    <a href="{{ route('front.singleProduct', $p->slug) }}" class="text-decoration-none">
                                      <div class="product-image">
                                          @if($p->sale_price && $p->sale_price < $p->base_price)
                                              <span class="product-badge">{{ round((($p->base_price - $p->sale_price) / $p->base_price) * 100) }}% OFF</span>
                                          @endif
                                          <img src="{{ asset('storage/'.$p->main_image) }}" alt="{{ $p->name }}">
                                          <span class="product-rating">⭐ 5.0</span>
                                      </div>
                                    </a>
                                      <div class="product-info">
                                        <a href="{{ route('front.singleProduct', $p->slug) }}" class="text-decoration-none">
                                          <h6 class="product-brand">{{ $p->brand->name ?? '' }}</h6>
                                          <p class="product-title text-truncate">{{ $p->name }}</p>
                                        </a>
                                          <div class="product-price">
                                              <div>
                                                  <span class="price">₹{{ $p->sale_price ?? $p->base_price }}</span>
                                                  @if($p->sale_price) <span class="old-price">₹{{ $p->base_price }}</span> @endif
                                              </div>
                                              <div class="product-action"><button class="add-btn">Add</button></div>
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          </div>

                          <button class="deals-arrow right">❯</button>
                      </div>
                    </div>

                  </div>
              @endforeach
          </div>
        </section>

      {{-- 3. STANDARD GRID OR SCROLL (Brands & Categories) --}}
      @else
        <section class="mt-5 {{ $section->type === 'category' ? 'categories' : 'brand-section' }}">
            <div class="container-xxl">
                <h2 class="fw-bold">{{ $section->title }}</h2>
                <p class="{{ $section->type === 'category' ? 'category-subtitle' : 'section-subtitle' }}">
                    {{ $section->subtitle }}
                </p>

                <div class="{{ $isScroll ? 'category-scroll' : 'row g-4' }}">
                    @foreach($section->items as $item)
                        @php
                            $id = (int) $item->item_id;
                            $data = ($section->type === 'brand') ? ($brands[$id] ?? null) : ($categories[$id] ?? null);
                            $title = $item->title ?? ($data->name ?? 'Item');
                        @endphp

                        {{-- Use Grid Column wrapper only if not scrolling --}}
                        @if(!$isScroll) <div class="{{ $gridClass }}"> @endif

                            <a href="{{ $item->link ?? '#' }}" class="{{ $section->type === 'category' ? 'category-item' : 'brand-card' }} text-decoration-none">
                                <div class="{{ $section->type === 'category' ? 'category-card shadow' : '' }}">
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                        class="{{ $section->type === 'brand' ? 'w-100' : '' }}" 
                                        alt="{{ $title }}" />
                                </div>
                                <div class="{{ $section->type === 'category' ? 'category-title' : 'text-center brand-card-text' }}">
                                    {{ $title }}
                                </div>
                            </a>

                        @if(!$isScroll) </div> @endif
                    @endforeach
                </div>
            </div>
        </section>
      @endif
  @endforeach

  {{-- Single Category Banner --}}
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

  {{-- Blog Section --}}
  <section class="blog-section">
    <div class="container-xxl">
      <h2 class="section-title">Explore the World of Wooflix</h2>

      <p class="section-subtitle">
        Bringing joy to pets and families, one home at a time!
      </p>

      <div class="row g-4">
          @forelse($latestBlogs as $post)
              <div class="col-6 col-md-3">
                  <a href="{{ url('blog/' . $post->slug) }}" class="blog-card">
                      <div class="blog-image">
                          <picture>
                              {{-- Try loading WebP first --}}
                              @if($post->webp_thumb)
                                  <source srcset="{{ asset('storage/' . $post->webp_thumb) }}" type="image/webp">
                              @endif

                              {{-- Fallback to original JPG/PNG --}}
                              <img 
                                  src="{{ asset('storage/' . $post->featured_image) }}" 
                                  class="img-fluid" 
                                  alt="{{ $post->image_alt ?? $post->title }}"
                                  loading="lazy"
                              />
                          </picture>
                      </div>

                      <p class="blog-title">{{ $post->title }}</p>
                  </a>
              </div>
          @empty
              <div class="col-12 text-center py-5">
                  <p class="text-muted">No blog posts available yet. Check back soon!</p>
              </div>
          @endforelse
      </div>
    </div>
  </section>


@endsection