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
            <a href="{{route('user.orders')}}"Order History</a>
          </li>
        </ol>
      </div>
    </nav>

    <!-- Profile -->
    <div class="container-xxl py-4">
      <div class="row g-4">

        <div class="col-lg-12">
          <!-- ORDERS -->

          <div class="profile-section">
            <div class="section-header">
                <h6>Order History</h6>
            </div>

            <div class="order-list">

                @forelse($orders as $order)

                    @php
                        $item = $order->items->first(); // show first item
                        $product = $item?->product;
                    @endphp

                    @if($item && $product)

                        <div class="order-card">

                            <img 
                                src="{{ asset('storage/' . $product->main_image) }}"
                                alt="{{ $product->name }}"
                            />

                            <div class="order-info">
                                <h6>{{ $product->name }}</h6>

                                <p class="text-muted">
                                    {{ ucfirst($order->status) }} 
                                    on {{ $order->created_at->format('M d') }}
                                </p>
                            </div>

                            <a 
                                href="{{ route('front.singleProduct', $product->slug) }}"
                                class="btn btn-sm btn-orange"
                            >
                                Buy Again
                            </a>

                        </div>

                    @endif

                @empty

                    <div class="text-muted text-center py-4">
                        No orders yet
                    </div>

                @endforelse

            </div>
          </div>

        </div>
      </div>
    </div>


@endsection