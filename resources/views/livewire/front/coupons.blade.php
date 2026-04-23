<div class="product-offers">

    <h5 class="offers-title">
        <svg width="20" height="20" class="w-5 h-5 inline-block mb-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
        </svg>
        Available Offers
    </h5>

    <div class="offers-grid">

        @forelse($coupons as $coupon)

            <div class="offer-card {{ $coupon->is_best ? 'best-offer' : '' }}">

                @if($coupon->is_best)
                    <span class="offer-ribbon">BEST</span>
                @endif

                <div class="offer-content">

                    <h6>{{ $coupon->display_title }}</h6>

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

                <button 
                    class="coupon-btn"
                    onclick="copyCoupon('{{ $coupon->code }}', this)"
                >
                    <span class="btn-text">Copy</span>
                </button>

            </div>

        @empty
            <p class="text-muted">No offers available at the moment.</p>
        @endforelse

    </div>

</div>