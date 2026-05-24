@extends('layouts.front')

@section('content')
<section class="thank-you-page py-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                    </div>
                </div>

                <h1 class="fw-bold mb-3">Order Confirmed!</h1>
                <p class="text-muted fs-5 mb-4">
                    Thank you for shopping with Wooflix, <strong>{{ auth()->user()->name }}</strong>! <br>
                    Your pet's goodies are being prepared for dispatch.
                </p>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row text-start">
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block text-uppercase">Order Number</small>
                                <span class="fw-bold">{{ $order->order_number }}</span>
                            </div>
                            <div class="col-6 mb-3 text-end">
                                <small class="text-muted d-block text-uppercase">Payment Status</small>
                                <span class="badge bg-success-subtle text-success px-3">Paid</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block text-uppercase">Estimated Delivery</small>
                                <span class="fw-bold">2-4 Business Days</span>
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('front.shop') }}" class="btn btn-orange px-4 py-2">Continue Shopping</a>
                    <a href="{{ route('user.orders') }}" class="btn btn-outline-dark px-4 py-2">View Order History</a>
                </div>

                <p class="mt-5 text-muted small">
                    A confirmation email has been sent to <strong>{{ auth()->user()->email }}</strong>.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection