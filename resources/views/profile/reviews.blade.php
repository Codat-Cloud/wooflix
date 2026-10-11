@extends('layouts.front')

@section('content')

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
      <div class="container-xxl">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/">Home</a>
          </li>

          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">My Account</a>
          </li>

          <li class="breadcrumb-item active" aria-current="page">
            My Reviews
          </li>
        </ol>
      </div>
    </nav>

    <!-- Reviews -->
    <div class="container-xxl py-4">
      <div class="row g-4">

        <div class="col-lg-12">
          <div class="profile-section">
            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                <h6 class="mb-0">Your Reviews</h6>
                <span class="text-muted small">Total: {{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }}</span>
            </div>

            <div class="order-list">

                @forelse($reviews as $review)

                    @php
                        $product = $review->product;
                    @endphp

                    <div class="order-card border rounded-4 p-3 mb-4 bg-white">
                        <div class="row g-4">

                            {{-- LEFT COLUMN: STATUS & RATING META --}}
                            <div class="col-lg-3">
                                <h6 class="mb-1">
                                    Review #{{ $review->id }}
                                </h6>

                                <p class="text-muted small mb-2">
                                    Submitted on {{ $review->created_at->format('d M Y') }}
                                </p>

                                {{-- MODERATION STATUS --}}
                                <div class="mb-3">
                                    @if($review->is_approved)
                                        <span class="badge bg-success px-3 py-2">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">
                                            Pending Approval
                                        </span>
                                    @endif
                                </div>

                                {{-- STAR RATING --}}
                                <div class="mb-2">
                                    <span class="text-warning fs-5">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                    <span class="fw-bold ms-1 text-dark">{{ number_format($review->rating, 1) }} / 5</span>
                                </div>

                                {{-- VERIFIED BADGE --}}
                                @if($review->is_verified_buyer)
                                    <div class="mb-2">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            ✔ Verified Buyer
                                        </span>
                                    </div>
                                @endif

                                <div class="small text-muted mt-2">
                                    Posted by: <strong>{{ $review->customer_name }}</strong>
                                </div>
                            </div>

                            {{-- CENTER COLUMN: PRODUCT & REVIEW COMMENT --}}
                            <div class="col-lg-6">
                                <div class="order-product py-1">
                                    <div class="d-flex gap-3 align-items-start">

                                        {{-- PRODUCT IMAGE --}}
                                        @if($product)
                                            <div>
                                                <a href="{{ route('front.singleProduct', $product->slug) }}">
                                                    <img
                                                        src="{{ asset('storage/' . $product->main_image) }}"
                                                        alt="{{ $product->name }}"
                                                        style="
                                                            width: 75px;
                                                            height: 75px;
                                                            object-fit: cover;
                                                            border-radius: 12px;
                                                        "
                                                    >
                                                </a>
                                            </div>
                                        @endif

                                        {{-- PRODUCT DETAILS & COMMENT --}}
                                        <div class="flex-grow-1">
                                            @if($product)
                                                <a class="text-decoration-none text-dark" href="{{ route('front.singleProduct', $product->slug) }}">
                                                    <h6 class="mb-1 fw-bold">
                                                        {{ $product->name }}
                                                    </h6>
                                                </a>
                                                <div class="small text-muted mb-2">
                                                    {{ $product->brand->name ?? 'Wooflix' }}
                                                </div>
                                            @else
                                                <h6 class="mb-1 text-muted">[Product no longer available]</h6>
                                            @endif

                                            {{-- COMMENT TEXT --}}
                                            <div class="p-3 bg-light rounded-3 mt-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
                                                "{{ $review->comment }}"
                                            </div>

                                            {{-- CUSTOMER UPLOADED IMAGES --}}
                                            @if($review->images && $review->images->isNotEmpty())
                                                <div class="d-flex gap-2 flex-wrap mt-3">
                                                    @foreach($review->images as $img)
                                                        <img
                                                            src="{{ asset('storage/' . $img->image_path) }}"
                                                            alt="Customer photo"
                                                            class="rounded border"
                                                            style="width: 60px; height: 60px; object-fit: cover;"
                                                        >
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- ADMIN RESPONSE --}}
                                            @if(!empty($review->reply))
                                                <div class="mt-3 p-3 bg-white rounded border-start border-warning border-4 shadow-sm">
                                                    <small class="d-block fw-bold text-dark mb-1">Wooflix Team Response:</small>
                                                    <small class="text-muted">{{ $review->reply }}</small>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT COLUMN: ACTIONS & FEEDBACK SUMMARY --}}
                            <div class="col-lg-3">
                                <div class="border rounded-4 p-3 bg-light w-100 h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="small text-muted mb-1">
                                            Helpful Feedback
                                        </div>

                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <span class="badge bg-white text-dark border px-2 py-1">
                                                👍 {{ $review->likes }} {{ Str::plural('Like', $review->likes) }}
                                            </span>
                                        </div>

                                        <div class="small text-muted mb-1">
                                            Review Visibility
                                        </div>

                                        <div class="small mb-4 text-dark">
                                            {{ $review->is_approved ? 'Publicly visible on product page' : 'Under moderation review' }}
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        @if($product)
                                            <a
                                                href="{{ route('front.singleProduct', $product->slug) }}"
                                                class="btn btn-outline-dark"
                                            >
                                                View Product
                                            </a>

                                            <a
                                                href="{{ route('front.singleProduct', $product->slug) }}"
                                                class="btn btn-orange text-white fw-bold"
                                            >
                                                Buy Again
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                @empty

                    <div class="text-center text-muted py-5 border rounded-4 bg-white">
                        <div class="fs-1 mb-2">🐾</div>
                        <h6 class="text-dark">No reviews found</h6>
                        <p class="small text-muted mb-3">You haven't submitted any product reviews yet.</p>
                        <a href="{{ route('user.orders') }}" class="btn btn-orange text-white btn-sm px-4">
                            Review Past Orders
                        </a>
                    </div>

                @endforelse

            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>

          </div>
        </div>

      </div>
    </div>

@endsection