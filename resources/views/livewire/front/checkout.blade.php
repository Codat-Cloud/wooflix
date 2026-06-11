
    <!-- Checkout -->
    <section class="checkout-page container-xxl my-4">
      <div class="row g-4">
        <!-- LEFT SIDE -->

        <div class="col-lg-8">
          <h4 class="mb-3">Checkout</h4>

          <!-- ADDRESS -->

        @auth
        <div class="checkout-card address-section bg-white p-4">
            <h6 class="fw-bold mb-3 text-dark">Primary Delivery Address</h6>

            <div class="address-list">
                @if($defaultAddress)
                    <div class="address-card active border-orange border p-3 rounded bg-light bg-opacity-10">
                        <div class="address-content">
                            <strong class="text-dark d-block mb-1">{{ $defaultAddress->address_line1 }}</strong>
                            <p class="text-muted small mb-0">
                                {{ $defaultAddress->name }}<br>
                                {{ $defaultAddress->city }}, {{ $defaultAddress->state }} - {{ $defaultAddress->postal_code }}<br>
                                📞 {{ $defaultAddress->phone }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-muted small py-2">No addresses found matching profile information.</div>
                @endif
            </div>

            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark w-100 mt-3">+ Manage Addresses</a>
        </div>
        @endauth


        @guest
          <div class="checkout-card bg-white p-4">
              <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                  <span>Checkout Details</span>
                  <span class="badge bg-light text-dark font-normal small fw-normal border ms-auto" style="font-size: 0.75rem;">Guest Mode</span>
              </h6>
              <p class="text-muted small mb-4">Provide your information below. A permanent account will be initialized and verified upon completing checkout payment securely.</p>

              <div class="row g-3">
                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-muted mb-1">Your Full Name</label>
                      <input type="text" wire:model.defer="guest_name" class="form-control shadow-none @error('guest_name') is-invalid @enderror" placeholder="Alex Logan" required>
                      @error('guest_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-6">
                      <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                      <input type="email" wire:model.defer="guest_email" class="form-control shadow-none @error('guest_email') is-invalid @enderror" placeholder="name@example.com" required>
                      @error('guest_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-12">
                      <label class="form-label small fw-bold text-muted mb-1">Contact Phone Number</label>
                      <input type="tel" wire:model.defer="guest_phone" class="form-control shadow-none @error('guest_phone') is-invalid @enderror" placeholder="9876543210" required pattern="[0-9]{10}">
                      @error('guest_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-12">
                      <label class="form-label small fw-bold text-muted mb-1">Street Delivery Address Details</label>
                      <input type="text" wire:model.defer="guest_address" class="form-control shadow-none @error('guest_address') is-invalid @enderror" placeholder="Flat, House no., Apartment, Street Lane" required>
                      @error('guest_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-4">
                      <label class="form-label small fw-bold text-muted mb-1">City</label>
                      <input type="text" wire:model.defer="guest_city" class="form-control shadow-none @error('guest_city') is-invalid @enderror" placeholder="New Delhi" required>
                      @error('guest_city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-4">
                      <label class="form-label small fw-bold text-muted mb-1">State</label>
                      <input type="text" wire:model.defer="guest_state" class="form-control shadow-none @error('guest_state') is-invalid @enderror" placeholder="Delhi" required>
                      @error('guest_state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-md-4">
                      <label class="form-label small fw-bold text-muted mb-1">Pincode / Postal Code</label>
                      <input type="text" wire:model.defer="guest_postal_code" class="form-control shadow-none @error('guest_postal_code') is-invalid @enderror" placeholder="110016" required maxlength="6"">
                      @error('guest_postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
              </div>
          </div>
      @endguest
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

            <button class="btn btn-orange w-100 mt-3 py-2 fw-bold text-white"
                wire:click="placeOrder"
                wire:loading.attr="disabled"
                wire:target="placeOrder"
                style="background-color: #f26522; border: 0; border-radius: 6px;">
                
                <span wire:loading.remove wire:target="placeOrder">
                    Proceed to Payment
                </span>
                
                <span wire:loading wire:target="placeOrder">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Initializing Gateway...
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