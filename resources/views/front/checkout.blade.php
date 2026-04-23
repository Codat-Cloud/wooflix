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
            <a href="{{route('front.checkout')}}">Checkout</a>
          </li>
        </ol>
      </div>
    </nav>

    <!-- Checkout -->
    <section class="checkout-page container-xxl my-4">
      <div class="row g-4">
        <!-- LEFT SIDE -->

        <div class="col-lg-8">
          <h4 class="mb-3">Checkout</h4>

          <!-- ADDRESS -->

          <div class="checkout-card address-section">
            <h6>Select Delivery Address</h6>

            <!-- ADDRESS LIST -->

            <div class="address-list">
              <label class="address-card active">
                <input type="radio" name="address" checked />

                <div class="address-content">
                  <strong>Home</strong>

                  <p>
                    Arjun Sharma<br />
                    Mithapur, Patna, Bihar - 800001<br />
                    📞 9876543210
                  </p>
                </div>

                <a class="edit-address" data-id="1">Edit</a>
              </label>

              <label class="address-card">
                <input type="radio" name="address" />

                <div class="address-content">
                  <strong>Office</strong>

                  <p>
                    Arjun Sharma<br />
                    Connaught Place, Delhi - 110001<br />
                    📞 9876543210
                  </p>
                </div>

                <a class="edit-address" data-id="2">Edit</a>
              </label>
            </div>

            <!-- ADD NEW ADDRESS -->

            <button
              class="btn btn-light w-100 mt-3"
              data-bs-toggle="collapse"
              data-bs-target="#addAddressForm"
            >
              * Add New Address
            </button>

            <div class="collapse mt-3" id="addAddressForm">
              <form class="row g-3">
                <div class="col-md-6">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Full Name"
                  />
                </div>

                <div class="col-md-6">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Phone Number"
                  />
                </div>

                <div class="col-12">
                  <textarea
                    class="form-control"
                    rows="2"
                    placeholder="Address"
                  ></textarea>
                </div>

                <div class="col-md-4">
                  <input type="text" class="form-control" placeholder="City" />
                </div>

                <div class="col-md-4">
                  <input type="text" class="form-control" placeholder="State" />
                </div>

                <div class="col-md-4">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Pincode"
                  />
                </div>

                <div class="col-12">
                  <button class="btn btn-orange w-100">Save Address</button>
                </div>
              </form>
            </div>
          </div>

          <!-- <div class="checkout-card">
            <h6>Select Delivery Address</h6>

            
            <div class="address-box active">
              <strong>Home</strong>
              <p>Arjun Sharma, Mithapur, Patna, Bihar - 800001</p>
            </div>

            <button
              class="btn btn-light w-100 mt-2"
              data-bs-toggle="collapse"
              data-bs-target="#addAddressForm"
            >
              + Add New Address
            </button>

            <div class="collapse mt-3" id="addAddressForm">
              <form class="row g-3">
                <div class="col-md-6">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Full Name"
                  />
                </div>

                <div class="col-md-6">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Phone Number"
                  />
                </div>

                <div class="col-12">
                  <textarea
                    class="form-control"
                    rows="2"
                    placeholder="Address"
                  ></textarea>
                </div>

                <div class="col-md-4">
                  <input type="text" class="form-control" placeholder="City" />
                </div>

                <div class="col-md-4">
                  <input type="text" class="form-control" placeholder="State" />
                </div>

                <div class="col-md-4">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Pincode"
                  />
                </div>

                <div class="col-12">
                  <button class="btn btn-orange w-100">Save Address</button>
                </div>
              </form>
            </div>
          </div> -->

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
              <input type="radio" name="delivery" />
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
            <h5>Order Summary</h5>

            <div class="summary-item">
              <span>Subtotal</span>
              <span>₹473</span>
            </div>

            <div class="summary-item">
              <span>Shipping</span>
              <span class="text-success">Free</span>
            </div>

            <div class="summary-item total">
              <span>Total</span>
              <span>₹473</span>
            </div>

            <button class="btn btn-orange w-100 mt-3">Place Order</button>
          </div>
        </div>
      </div>
    </section>

    <div class="mobile-paybar d-lg-none">
      <div class="total">₹473</div>

      <button class="btn btn-orange">Place Order</button>
    </div>
    
@endsection