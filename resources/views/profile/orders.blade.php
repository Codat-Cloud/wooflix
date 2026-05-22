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
            <a href="{{route('user.orders')}}">Order History</a>
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

                    <div class="order-card border rounded-4 p-3 mb-4 bg-white">

                        <div class="row g-4">

                            {{-- LEFT SIDE --}}
                            <div class="col-lg-3">

                                <h6 class="mb-1">

                                    Order #{{ $order->order_number }}

                                </h6>

                                <p class="text-muted small mb-2">

                                    Placed on
                                    {{ $order->created_at->format('d M Y') }}

                                </p>

                                {{-- STATUS --}}
                                <div class="mb-3">

                                    <span class="badge bg-success px-3 py-2">

                                        {{ ucfirst($order->status) }}

                                    </span>

                                </div>

                                {{-- PAYMENT --}}
                                <p class="small mb-2">

                                    <strong>Payment:</strong>
                                    {{ ucfirst($order->payment_status) }}

                                </p>

                                {{-- SHIPPING --}}
                                <div class="small text-muted">

                                    Delivering To

                                </div>

                                <div class="fw-semibold">

                                    {{ $order->shipping_name }}

                                </div>

                                <div class="small text-muted">

                                    {{ $order->shipping_city }},
                                    {{ $order->shipping_state }}

                                    -
                                    {{ $order->shipping_postal_code }}

                                </div>

                                {{-- TRACKING --}}
                                @if($order->tracking_number)

                                    <div class="mt-3">

                                        <div class="small text-muted">

                                            Tracking Number

                                        </div>

                                        <div class="fw-semibold">

                                            {{ $order->tracking_number }}

                                        </div>

                                    </div>

                                @endif

                            </div>

                            {{-- CENTER ITEMS --}}
                            <div class="col-lg-6">

                                @foreach($order->items as $item)

                                    @php
                                        $product = $item->product;
                                    @endphp

                                    @if($product)

                                        <div class="order-product py-3">

                                            <div class="d-flex gap-3 align-items-start">

                                                {{-- IMAGE --}}
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

                                                {{-- CONTENT --}}
                                                <div class="flex-grow-1">

                                                <a class="text-decoration-none text-dark" href="{{ route('front.singleProduct', $product->slug) }}">
                                                    <h6 class="mb-1">

                                                        {{ $product->name }}

                                                    </h6>
                                                </a>

                                                    <div class="small text-muted mb-1">

                                                        Quantity:
                                                        {{ $item->quantity }}

                                                    </div>

                                                    @if($item->variant)

                                                        <div class="small text-muted">

                                                            Variant:
                                                            {{ $item->variant->name }}

                                                        </div>

                                                    @endif

                                                </div>

                                                {{-- PRICE --}}
                                                <div class="text-end">

                                                    <div class="fw-bold mb-2">

                                                        ₹{{ number_format($item->price) }}

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                @endforeach

                            </div>

                            {{-- RIGHT SUMMARY --}}
                            <div class="col-lg-3">

                                <div class="border rounded-4 p-3 bg-light w-100">

                                    <div class="mb-3">

                                        <div class="small text-muted">

                                            Order Total

                                        </div>

                                        <h4 class="mb-0 fw-bold">

                                            ₹{{ number_format($order->total_amount) }}

                                        </h4>

                                    </div>

                                    <div class="small text-muted mb-1">

                                        Payment Method

                                    </div>

                                    <div class="mb-4">

                                        {{ ucfirst($order->payment_method) }}

                                    </div>

                                    <div class="d-grid gap-2">

                                        {{-- TRACK --}}
                                        @if($order->tracking_url)

                                            <a
                                                href="{{ $order->tracking_url }}"
                                                target="_blank"
                                                class="btn btn-outline-dark"
                                            >

                                                Track Order {{$order->tracking_number}}

                                            </a>

                                        @endif

                                        {{-- INVOICE --}}
                                        <a
                                            href="{{ route('front.orderInvoice', $order->id) }}"
                                            target="_blank"
                                            class="btn btn-outline-secondary"
                                        >

                                            Invoice

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-5">

                        No orders found

                    </div>

                @endforelse

                
            </div>
            
            
            </div>
            <div class="mt-4">

            {{ $orders->links() }}

            </div>

        </div>
      </div>
    </div>


@endsection