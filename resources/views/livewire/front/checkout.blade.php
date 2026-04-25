
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

            <label class="delivery-option active">
              <input type="radio" name="delivery" checked />
              <div>
                <strong>Standard Delivery</strong>
                <p>Delivery by Tomorrow</p>
              </div>
              <span class="text-success">Free</span>
            </label>

            <label class="delivery-option">
                <input type="radio" wire:model="deliveryType" value="standard" />
                <div>
                    <strong>Standard Delivery</strong>
                    <p>Delivery by Tomorrow</p>
                </div>
                <span class="text-success">Free</span>
            </label>

            <label class="delivery-option">
                <input type="radio" wire:model="deliveryType" value="express" />
                <div>
                    <strong>Express Delivery</strong>
                    <p>Get it Today</p>
                </div>
                <span>₹49</span>
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

            <button class="btn btn-orange w-100 mt-3"
              wire:click="placeOrder"
              wire:loading.attr="disabled"
              >Place Order</button>

              <div class="coupon-inline mt-3">

                @if(!$appliedCoupon)

                    <div class="d-flex gap-2">

                        <input 
                            type="text"
                            wire:model.defer="couponCode"
                            class="form-control"
                            placeholder="Enter coupon code"
                        >

<button 
    type="button" 
    wire:click.prevent="applyCoupon"
    wire:loading.attr="disabled"
    class="btn btn-outline-dark"
>
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
      >Place Order</button>
    </div>
    

    @push('script')
    <script>
        window.addEventListener('close-address-form', () => {
            const el = document.getElementById('addAddressForm');
            const collapse = bootstrap.Collapse.getInstance(el);
            if (collapse) collapse.hide();
        });
    </script>
    @endpush