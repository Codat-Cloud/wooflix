@extends('layouts.front')

@section('content')
{{-- <pre>
{{ dd($product->variants->toArray()) }}
</pre> --}}
@php
$variants = $product->variants->map(function ($v) {
    return [
        'id' => $v->id,
        'name' => $v->optionValues->pluck('value')->join(' / '), // 🔥 CORRECT
        'price' => $v->price,
        'sale_price' => $v->sale_price,
    ];
});
@endphp

<script>
    window.productVariants = {!! json_encode($variants) !!};

    window.productBase = {
        price: {{ $product->base_price }},
        sale_price: {{ $product->sale_price ?? 'null' }}
    };

</script>

@php
    // Check by user_id if logged in, otherwise session_id
    $cartQuery = \App\Models\CartItem::query();
    if(auth()->check()) {
        $cartQuery->where('user_id', auth()->id());
    } else {
        $cartQuery->where('session_id', session()->getId());
    }
    
    $cartItemsInDB = $cartQuery->get();
    $cartVariantIds = $cartItemsInDB->pluck('variant_id')->filter()->toArray();
    $cartProductIds = $cartItemsInDB->whereNull('variant_id')->pluck('product_id')->toArray();
@endphp

<script>
    window.cartVariantIds = @json($cartVariantIds);
    window.cartProductIds = @json($cartProductIds);
</script>


    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
        <div class="container-xxl">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('front.shop', ['cat' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </div>
    </nav>

        <!-- Product Details -->

    <section class="single-product-info">
      <div class="container-xxl product-page">
        <div class="row g-4">
          <!-- PRODUCT IMAGES -->

          <div class="col-lg-6">
              <div class="product-gallery" 
                x-data="{ 
                    activeIndex: 0, 
                    {{-- Merge main_image with the collection of secondary images --}}
                    images: [
                        '{{ asset('storage/' . $product->main_image) }}',
                        @foreach($product->images as $img)
                            '{{ asset('storage/' . $img->image) }}',
                        @endforeach
                    ],
                    next() {
                        this.activeIndex = (this.activeIndex + 1) % this.images.length;
                    },
                    prev() {
                        this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                    }
                }">
                
                <div class="gallery-main">
                    {{-- Only show arrows if there is more than one image --}}
                    <template x-if="images.length > 1">
                        <button class="gallery-arrow left" @click="prev()"> ← </button>
                    </template>

                    <div class="image-frame">
                        <img id="mainImage"
                            :src="images[activeIndex]"
                            class="main-product-img" 
                            alt="{{ $product->name }}" />
                    </div>

                    <template x-if="images.length > 1">
                        <button class="gallery-arrow right" @click="next()"> → </button>
                    </template>
                </div>

                <div class="gallery-thumbs">
                    <template x-for="(img, index) in images" :key="index">
                        <img :src="img"
                            class="thumb"
                            :class="activeIndex === index ? 'active' : ''"
                            @click="activeIndex = index" />
                    </template>
                </div>
            </div>
          </div>

          <!-- PRODUCT INFO -->

          <div class="col-lg-6">
            <div class="product-info">
              <div class="single-product-rating">
                  @php $stats = $product->rating_stats; @endphp
                  <span class="rating-stars">
                      ⭐ {{ number_format($stats['average'], 1) }}
                  </span>

                  <span class="rating-divider">|</span>

                  <span class="rating-count">
                      {{ $stats['total'] }} {{ Str::plural('review', $stats['total']) }}
                  </span>
              </div>

              <h1 class="single-product-title">{{ $product->name }}</h1>

              <h2 class="product-brand">
                  <b class="text-dark">From </b>
                  {{ $product->brand->name ?? 'Wooflix' }}
              </h2>

              <!-- Price -->
              <div x-data="{
                  variants: window.productVariants || [],
                  selected: null,
                  inCartVariants: window.cartVariantIds || [],
                  inCartProducts: window.cartProductIds || [],
                  price: 0,
                  mrp: null,
                  adding: false,
                  added: false,

                  init() {
                      if (this.variants.length > 0) {
                          this.select(this.variants[0]);
                      } else {
                          this.price = window.productBase.sale_price ?? window.productBase.price;
                          this.mrp = window.productBase.sale_price ? window.productBase.price : null;
                      }
                  },

                  select(v) {
                      this.selected = v;
                      this.price = v.sale_price ?? v.price;
                      this.mrp = v.sale_price ? v.price : null;
                  },

                  isAdded() {
                      if (this.variants.length > 0) {
                          return this.selected ? this.inCartVariants.includes(this.selected.id) : false;
                      }
                      return this.inCartProducts.includes({{ $product->id }});
                  },

                  discount() {
                      if (!this.mrp) return null;
                      return Math.round(((this.mrp - this.price) / this.mrp) * 100);
                  }

              }" 
              @cart-updated.window="inCartVariants = [...($event.detail.variant_ids || [])];
inCartProducts = [...($event.detail.product_ids || [])]; adding = false; added = true; setTimeout(() => added = false, 2000);"
              >

              <!-- PRICE -->
              <div class="product-price border-bottom">
                  <div>

              <span class="price" x-text="'₹' + price"></span>

                          <template x-if="mrp">
                              <span class="old-price" x-text="'MRP: ₹' + mrp"></span>
                          </template>

                          <template x-if="mrp">
                              <span class="discount" x-text="'(' + discount() + '% OFF)'"></span>
                          </template>

                      </div>

                      <div>
                          <button class="wishlist-btn">♡</button>
                      </div>
                  </div>

                  <!-- VARIANTS -->
              <div class="product-variants py-2" x-show="variants.length > 0">
                  <h6 class="fw-bold">Select</h6>

                  <div class="variant-options">

                    <template x-for="v in variants" :key="v.id">

                        <div class="variant-item">

                            <button 
                                class="variant-btn"
                                :class="selected?.id === v.id ? 'active' : ''"
                                @click="select(v)"
                            >

                                <span x-text="v.name"></span>

                                <template x-if="v.sale_price">
                                    <span class="variant-badge" 
                                          x-text="Math.round(((v.price - v.sale_price)/v.price)*100) + '% OFF'">
                                    </span>
                                </template>

                            </button>

                        </div>

                    </template>

                  </div>
              </div>

              <button 
                class="btn add-cart-btn w-100"

                :class="{
                    'btn-orange': !adding && !isAdded(),
                    'btn-secondary': adding,
                    'btn-success': isAdded()
                }"

                @click="
                    if (adding || isAdded()) return;

                    if (variants.length > 0 && !selected) {
                        alert('Select variant');
                        return;
                    }

                    adding = true;

                    window.dispatchEvent(new CustomEvent('add-to-cart', {
                        detail: [
                            selected ? selected.id : null,
                            variants.length === 0 ? {{ $product->id }} : null
                        ]
                    }));
                "
            >
                <span x-show="!adding && !isAdded()">Add To Cart</span>
                <span x-show="adding">Adding...</span>
                <span x-show="isAdded()">Already in Cart ✓</span>
            </button>
              </div>



            <!-- OFFERS -->
            <div class="product-offers">
                <h5 class="offers-title">
                  <svg width="20" height="20" class="w-5 h-5 inline-block mb-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                  </svg>
                   Available Offers</h5>

                <div class="offers-grid">
                    @forelse($coupons as $coupon)
                        <div class="offer-card {{ $coupon->is_best ? 'best-offer' : '' }}">
                            @if($coupon->is_best)
                                <span class="offer-ribbon">BEST</span>
                            @endif

                            <div class="offer-content">
                                {{-- Uses the Accessor we made: display_title --}}
                                <h6>{{ $coupon->display_title }}</h6>
                                
                                {{-- Fallback description logic --}}
                                <p>
                                    @if($coupon->description)
                                        {{ $coupon->description }}
                                    @elseif($coupon->min_spend > 0)
                                        Orders above ₹{{ number_format($coupon->min_spend, 0) }}
                                    @else
                                        Use coupon on checkout
                                    @endif
                                </p>
                            </div>

                            <button class="coupon-btn" data-code="{{ $coupon->code }}" onclick="copyCoupon(this)">
                                <span class="btn-text">Copy</span>
                            </button>
                        </div>
                    @empty
                        <p class="text-muted">No offers available at the moment.</p>
                    @endforelse
                </div>
            </div>

              <!-- DELIVERY CHECK -->
              <div class="delivery-info">
                <h5 class="delivery-title">Delivery & Service Information</h5>

                <div class="delivery-check">
                  <input type="text" value="560034" class="pincode-input" />

                  <button class="btn btn-orange delivery-btn">Check</button>
                </div>

                <div class="delivery-items">
                  <div class="delivery-item">
                    <span class="delivery-icon">⚡</span>
                    <span>Get it <strong>Today</strong></span>
                  </div>

                  <div class="delivery-item">
                    <span class="delivery-icon">🚚</span>
                    <span>Expected delivery date – <strong>Today</strong></span>
                  </div>

                  <div class="delivery-item">
                    <span class="delivery-icon">📦</span>
                    <span>No Exchange & Returns</span>
                  </div>

                  <div class="delivery-item">
                    <span class="delivery-icon free">FREE</span>
                    <span>Enjoy Free Delivery above <strong>₹699</strong></span>
                  </div>
                </div>
              </div>

              <!-- ACCORDION -->

              <div class="accordion product-accordion mt-3">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button collapsed"
                      data-bs-toggle="collapse"
                      data-bs-target="#details"
                    >
                      Product Details
                    </button>
                  </h2>

                  <div id="details" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                      {!!$product->short_description!!}
                    </div>
                  </div>
                </div>

                <div class="accordion-item mt-3">
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button collapsed"
                      data-bs-toggle="collapse"
                      data-bs-target="#additional"
                    >
                      Additional Information
                    </button>
                  </h2>

                  <div id="additional" class="accordion-collapse collapse">
                    <div class="accordion-body">
                      {!!$product->description!!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="reviews-section container-xxl my-5">
      <h4 class="mb-3">Customer Reviews</h4>

      <ul class="nav nav-tabs review-tabs">
        <li class="nav-item">
          <button
            class="nav-link active"
            data-bs-toggle="tab"
            data-bs-target="#reviewsTab"
          >
            Reviews
          </button>
        </li>

        <li class="nav-item">
          <button
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#writeReviewTab"
          >
            Write Review
          </button>
        </li>

        <li class="nav-item">
          <button
            class="nav-link"
            data-bs-toggle="tab"
            data-bs-target="#questionTab"
          >
            Ask Question?
          </button>
        </li>
      </ul>

      <div class="tab-content mt-4">
        <!-- REVIEWS TAB -->

        <div class="tab-pane fade show active" id="reviewsTab">
          <div class="">
            <div class="review-summary">
              @php $stats = $product->rating_stats; @endphp

              <div class="summary-left">
                  {{-- Display the dynamic average (e.g., 4.8) --}}
                  <h2 class="rating-average">{{ number_format($stats['average'], 1) }}</h2>

                  <div class="rating-stars">
                      {{-- Dynamic Star Logic based on average --}}
                      @for ($i = 1; $i <= 5; $i++)
                          @if ($i <= round($stats['average']))
                              ★
                          @else
                              <span style="color: #ccc;">★</span>
                          @endif
                      @endfor
                  </div>

                  <p class="total-reviews">Based on {{ $stats['total'] }} {{ Str::plural('review', $stats['total']) }}</p>
              </div>

              <div class="summary-bars">
                  {{-- Loop through the details (5 down to 1) --}}
                  @foreach($stats['details'] as $star => $data)
                      <div class="rating-row">
                          <span>{{ $star }} ★</span>
                          <div class="rating-bar">
                              {{-- Dynamic width based on the calculated percentage --}}
                              <div class="rating-fill" style="width: {{ $data['percentage'] }}%"></div>
                          </div>
                          <span class="rating-count">{{ $data['count'] }}</span>
                      </div>
                  @endforeach
              </div>
          </div>
          </div>

          <div class="review-gallery">
              @php $imgIndex = 0; @endphp

              @foreach($product->reviews as $review)
                  @foreach($review->images as $image)
                      <img 
                          src="{{ asset('storage/' . $image->image_path) }}" 
                          class="review-thumb"
                          onclick="openReviewGallery({{ $imgIndex }})"
                          alt="Customer photo"
                          lazyload="lazy"
                      >
                      @php $imgIndex++; @endphp
                  @endforeach
              @endforeach

              @if($imgIndex === 0)
                  <p class="text-muted small">No customer photos yet. Be the first!</p>
              @endif
          </div>

          <div class="row g-3 mt-3">
              @forelse($product->reviews as $review)
                  <div class="col-md-6">
                      <div class="review-card">
                          <div class="review-header">
                              <div>
                                  <strong>{{ $review->customer_name }}</strong>

                                  @if($review->is_verified_buyer)
                                      <span class="verified-badge">✔ Verified Buyer</span>
                                  @endif
                              </div>

                              <span class="review-rating text-warning">
                                  {{-- Output solid stars based on rating --}}
                                  @for($i = 1; $i <= 5; $i++)
                                      {{ $i <= $review->rating ? '★' : '☆' }}
                                  @endfor
                              </span>
                          </div>

                          <p>{{ $review->comment }}</p>

                          {{-- If the review has images, you can show them here --}}
                          @if($review->images->count() > 0)
                              <div class="review-images-mini mt-2 d-flex gap-2">
                                  @foreach($review->images as $rImg)
                                      <img src="{{ asset('storage/' . $rImg->image_path) }}" 
                                          alt="Review Photo" 
                                          class="rounded" 
                                          lazyload="lazy"
                                          style="width: 50px; height: 50px; object-fit: cover;">
                                  @endforeach
                              </div>
                          @endif
                      </div>
                  </div>
              @empty
                  <div class="col-12 text-center py-4">
                      <p class="text-muted">No reviews yet. Be the first to share your thoughts!</p>
                  </div>
              @endforelse
          </div>

          
        </div>

        <!-- WRITE REVIEW -->

        <div class="tab-pane fade" id="writeReviewTab">
          {{-- @livewire('front.write-review') --}}
          <livewire:front.write-review :productId="$product->id" />

        </div>

        <!-- ASK QUESTION -->

        <div class="tab-pane fade" id="questionTab">

          <div class="row g-4 my-4">
            <div class="col-12">
                <h5 class="fw-bold mb-4">Questions & Answers ({{ $product->questions->count() }})</h5>
            </div>

            @forelse($product->questions as $q)
                <div class="col-md-6">
                    <div class="review-card h-100 shadow-sm border-0 p-3 bg-white">
                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">Q</span>
                            <div>
                                <strong class="d-block text-dark">{{ $q->question }}</strong>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    By {{ $q->name }} • {{ $q->created_at->format('d M, Y') }}
                                </small>
                            </div>
                        </div>

                        @if($q->answer)
                        <div class="admin-answer-box p-3 rounded" style="background-color: #fff9f0; border-left: 3px solid #ff9800;">
                            <div class="d-flex gap-2">
                                <span class="badge bg-orange rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; color: white;">A</span>
                                <div>
                                    <p class="mb-0 text-dark small">{{ $q->answer }}</p>
                                    <small class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">
                                        — Wooflix Expert Team • {{ $q->updated_at->format('d M, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center p-5 border rounded bg-light">
                        <i class="bi bi-chat-dots fs-1 text-muted"></i>
                        <p class="mt-2 text-muted italic">Have a question about this product? Ask us below!</p>
                    </div>
                </div>
            @endforelse
        </div>


          <livewire:front.ask-question :productId="$product->id" />
          {{-- <form class="review-form">
            <div class="row g-3">
              <div class="col-md-6">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Your Name"
                />
              </div>

              <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Email" />
              </div>

              <div class="col-md-12">
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Ask your question"
                ></textarea>
              </div>

              <div class="col-md-12">
                <button class="btn btn-orange">Submit Question</button>
              </div>
            </div>
          </form> --}}
        </div>
      </div>
    </section>

    <div class="modal fade" id="reviewGalleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark border-0">
                <div class="modal-body p-0 position-relative text-center">
                    {{-- Navigation Arrows --}}
                    <button class="gallery-arrow left" onclick="changeReviewImage(-1)">&#10094;</button>
                    
                    <img id="reviewGalleryImage" class="img-fluid rounded" style="max-height: 80vh;">

                    <button class="gallery-arrow right" onclick="changeReviewImage(1)">&#10095;</button>

                    {{-- Close Button --}}
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Dynamically build the array from PHP
                const reviewImages = [
                    @foreach($product->reviews as $review)
                        @foreach($review->images as $image)
                            "{{ asset('storage/' . $image->image_path) }}",
                        @endforeach
                    @endforeach
                ];

                let reviewImageIndex = 0;

            });

            function openReviewGallery(index) {
                reviewImageIndex = index;
                
                // Set the initial image
                document.getElementById("reviewGalleryImage").src = reviewImages[index];

                // Show the modal (Standard Bootstrap 5 way)
                const modalElement = document.getElementById('reviewGalleryModal');
                const myModal = new bootstrap.Modal(document.getElementById('reviewGalleryModal'));
                myModal.show();
            }

            function changeReviewImage(step) {
                reviewImageIndex += step;

                // Loop back logic
                if (reviewImageIndex < 0) reviewImageIndex = reviewImages.length - 1;
                if (reviewImageIndex >= reviewImages.length) reviewImageIndex = 0;

                // Update the image src
                document.getElementById("reviewGalleryImage").src = reviewImages[reviewImageIndex];
            }
        </script>
    @endpush
 
@endsection