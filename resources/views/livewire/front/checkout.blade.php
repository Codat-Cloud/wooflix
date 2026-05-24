
    <!-- Checkout -->
    <section class="checkout-page container-xxl my-4">
      <div class="row g-4">
        <!-- LEFT SIDE -->

        <div class="col-lg-8">
          <h4 class="mb-3">Checkout</h4>

          <!-- ADDRESS -->

          <div class="checkout-card address-section">
            <h6>Primary Delivery Address</h6>

            <!-- ADDRESS LIST -->

            <div class="address-list">

                @if($defaultAddress)

                    <div class="address-card active">
                        <div class="address-content">
                            <strong>{{ $defaultAddress->address_line1 }}</strong>

                            <p>
                                {{ $defaultAddress->name }}<br>
                                {{ $defaultAddress->city }}, {{ $defaultAddress->state }} - {{ $defaultAddress->postal_code }}<br>
                                📞 {{ $defaultAddress->phone }}
                            </p>
                        </div>
                    </div>

                @else

                    <div class="text-muted">
                        No address found. Please add one.
                    </div>

                @endif

            </div>

            <!-- ADD NEW ADDRESS -->

            <a  href="{{route('dashboard')}}" class="btn btn-light w-100 mt-3">
                  + Manage Your Address
            </a>

          </div>

          <!-- DELIVERY -->

          <div class="checkout-card">
              <h6>Delivery Options</h6>

              <label class="delivery-option {{ $deliveryType === 'standard' ? 'active' : '' }}">
                  <input type="radio" wire:model.live="deliveryType" value="standard" />
                  <div>
                      <strong>Standard Delivery</strong>
                      <p class="mb-0">
                          @if($subtotal >= 699)
                              <span class="text-success fw-bold">Eligible for Free Shipping!</span>
                          @else
                              <span>Add <strong>₹{{ 699 - $subtotal }}</strong> more for Free Delivery</span>
                          @endif
                      </p>
                  </div>
                  <span class="fw-bold {{ $subtotal >= 699 ? 'text-success' : '' }}">
                      {{ $subtotal >= 699 ? 'Free' : '₹85' }}
                  </span>
              </label>

              <label class="delivery-option {{ $deliveryType === 'express' ? 'active' : '' }}">
                  <input type="radio" wire:model.live="deliveryType" value="express" />
                  <div>
                      <strong>Express Delivery</strong>
                      <p>2 Day Delivery</p>
                  </div>
                  <span>₹180</span>
              </label>
          </div>


        </div>

        <!-- RIGHT SIDE -->

        <div class="col-lg-4">
          <div class="checkout-summary">
            <h5 class="fw-bold">Order Summary</h5>
            <hr>

            <div class="summary-item">
              <span>Subtotal</span>
              <span>₹{{ number_format($subtotal, 2) }}</span>
            </div>

            <div class="summary-item">
              <span>Shipping</span>
              <span>₹{{ number_format($shippingCost, 2) }}</span>
            </div>

            @if($discount > 0)
                <div class="summary-item text-success">
                    <span>Discount</span>
                    <span>- ₹{{ number_format($discount, 2) }}</span>
                </div>
            @endif

            <div class="summary-item total">
              <span>Total</span>
              <span>₹{{ number_format($total, 2) }}</span>
            </div>

<button class="btn w-100 mt-3"
    wire:click="placeOrder"
    wire:loading.attr="disabled"
    :class="{
        'btn-orange': {{ $defaultAddress ? 'true' : 'false' }},
        'btn-secondary text-white opacity-75': {{ !$defaultAddress ? 'true' : 'false' }}
    }"
    {{ !$defaultAddress ? 'disabled' : '' }}
>
    <span wire:loading.remove wire:target="placeOrder">
        {{ $defaultAddress ? 'Place Order' : '⚠️ Please Add An Address First' }}
    </span>
    
    <span wire:loading wire:target="placeOrder">
        <span class="spinner-border spinner-border-sm"></span> Processing...
    </span>
</button>

              <div class="coupon-inline mt-3">

                @if(!$appliedCoupon)

                    <div class="d-flex gap-2">

                        <input 
                            type="text"
                            wire:model.defer="couponCode"
                            class="form-control"
                            placeholder="Enter coupon code"
                        >

                        {{-- Coupon Apply --}}
                        <button 
                            type="button" 
                            wire:click.prevent="applyCoupon"
                            wire:loading.attr="disabled"
                            class="btn btn-outline-dark">
                            <span wire:loading.remove wire:target="applyCoupon">Apply</span>
                            <span wire:loading wire:target="applyCoupon" class="spinner-border spinner-border-sm"></span>
                        </button>

                    </div>

                    @if($couponError)
                        <small class="text-danger d-block mt-2">
                            {{ $couponError }}
                        </small>
                    @endif

                @else

                    <div class="d-flex justify-content-between align-items-center mt-2">

                        <span class="text-success">
                            {{ $appliedCoupon->code }} applied 🎉
                        </span>

                        <button 
                            wire:click="removeCoupon"
                            class="btn btn-sm btn-link text-danger"
                        >
                            Remove
                        </button>

                    </div>

                @endif

            </div>
          </div>



        </div>
      </div>
    </section>

<div class="mobile-paybar d-lg-none">
    <div class="total">₹{{ number_format($total, 2) }}</div>

    <button class="btn btn-orange"
        wire:click="placeOrder"
        wire:loading.attr="disabled"
        {{ !$defaultAddress ? 'disabled' : '' }}>
        
        <span wire:loading.remove wire:target="placeOrder">
            {{ $defaultAddress ? 'Place Order' : 'Add Address' }}
        </span>

        <span wire:loading wire:target="placeOrder">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Processing...
        </span>
    </button>
</div>
    

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Listen for the custom event dispatched from the Livewire component backend
    window.addEventListener('initiate-razorpay-payment', event => {
        const result = event.detail[0] || event.detail;

        try {
            const options = {
                "key": "{{ config('services.razorpay.key') }}",
                "amount": result.amount, 
                "currency": "INR",
                "name": "Wooflix",
                "description": "Order #" + result.order_number,
                "order_id": result.razorpay_order_id,
                "handler": function (response) {
                    const queryParams = new URLSearchParams({
                        order_id: result.order_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    });

                    window.location.href = `/payment/verify?${queryParams.toString()}`;
                },
                "prefill": {
                    "name": result.customer_name,
                    "email": result.customer_email,
                    "contact": result.customer_phone
                },
                "theme": { "color": "#ff6b35" }
            };

            const rzp = new Razorpay(options);
            rzp.open();

        } catch (error) {
            console.error("Razorpay Initialization Error:", error);
            alert("Could not load payment gateway. Please try again.");
        }
    });
</script>
@endpush