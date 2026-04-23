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
            <a href="/dogs">Cart</a>
          </li>
        </ol>
      </div>
    </nav>

    <!-- Cart -->

@if($items->isEmpty())

    <!-- EMPTY CART -->
    <section class="empty-cart text-center py-5">
        <img src="https://placehold.co/200x150" />
        <h5>Your cart is empty</h5>
        <a href="/" class="btn btn-orange mt-2">Shop Now</a>
    </section>

@else

    <!-- CART ITEMS -->
    <section class="cart-page container-xxl my-4">
        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-8">
                <h4 class="mb-3">
                    Shopping Cart ({{ $items->sum('quantity') }} items)
                </h4>

                @foreach($items as $item)

                    <div class="cart-item">

                        <img 
                            src="{{ asset('storage/' . ($item->variant->image ?? $item->product->main_image)) }}"
                            class="cart-img"
                        />

                        <div class="cart-info">
                            <h6>{{ $item->display_name }}</h6>

                            @if($item->variant_name)
                                <p class="cart-meta">{{ $item->variant_name }}</p>
                            @endif

                            <div class="cart-actions">
                                <div class="qty-box">
                                    <button class="qty-btn minus">−</button>
                                    <span class="qty">{{ $item->quantity }}</span>
                                    <button class="qty-btn plus">+</button>
                                </div>

                                <span class="remove">Remove</span>
                            </div>
                        </div>

                        <div class="cart-price">
                            ₹{{ number_format($item->price, 2) }}
                        </div>

                    </div>

                @endforeach

            </div>

            <!-- RIGHT SUMMARY -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h5>Order Summary</h5>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>
                            ₹{{ number_format($items->sum(fn($i) => $i->total), 2) }}
                        </span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span>
                            ₹{{ number_format($items->sum(fn($i) => $i->total), 2) }}
                        </span>
                    </div>

                    <a href="{{route('front.checkout')}}" class="btn btn-orange w-100 mt-3">
                        Proceed to Checkout
                    </a>
                </div>
            </div>

        </div>
    </section>

@endif

    {{-- <div class="mobile-checkout d-lg-none">
      <div class="mobile-total">₹473</div>

      <button class="btn btn-orange">Checkout</button>
    </div> --}}

@endsection