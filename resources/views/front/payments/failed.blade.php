@extends('layouts.front')

@section('content')
<section class="payment-failed-page py-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="mb-4">
                    <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>

                <h1 class="fw-bold mb-3">Payment Failed</h1>
                <p class="text-muted fs-5 mb-4">
                    Oops! We couldn't process your payment. This could be due to a technical glitch, incorrect card details, or insufficient funds.
                </p>

                <div class="card border-0 bg-light rounded-4 mb-4">
                    <div class="card-body p-4">
                        <p class="mb-0 text-dark">
                            Don't worry, your cart items are safe! Your order <strong>{{ $order->order_number }}</strong> is currently on hold.
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('front.checkout') }}" class="btn btn-orange px-4 py-2">Try Payment Again</a>
                    <a href="https://wa.me/YOUR_NUMBER" class="btn btn-outline-dark px-4 py-2">
                        <i class="bi bi-whatsapp me-2"></i>Contact Support
                    </a>
                </div>

                <div class="mt-5 border-top pt-4">
                    <p class="text-muted small">If money was deducted from your account, it will be automatically refunded by your bank within 5-7 working days.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection