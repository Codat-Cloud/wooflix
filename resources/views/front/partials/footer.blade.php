    <!-- USP Section -->
    <section class="usp-section">
      <div class="container-xxl">
        <div class="row text-center g-3">
          <div class="col-12 col-lg">
            <div class="usp-item">
              <img src="assets/icons/shipping.svg" alt="Free Shipping" />

              <h6>FREE SHIPPING</h6>

              <p>On Orders Above ₹499</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
              <img src="assets/icons/returns.svg" alt="Free Returns" />

              <h6>FREE RETURNS</h6>

              <p>Within 7 days (T&C Apply)</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
              <img src="assets/icons/payment.svg" alt="Secure Payment" />

              <h6>SECURE PAYMENT</h6>

              <p>Your Transaction is Secure</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
              <img src="assets/icons/support.svg" alt="Best Support" />

              <h6>BEST SUPPORT</h6>

              <p>Mon - Fri. 9 AM to 9 PM</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
              <img src="assets/icons/delivery.svg" alt="Fast Delivery" />

              <h6>FAST DELIVERY</h6>

              <p>We Deliver on Time</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="container-xxl">
        <div class="row footer-top">
          <div class="col-6 col-md-3">
            <h6>SHOP FOR</h6>

            <ul>
              <li><a href="#">Dogs</a></li>
              <li><a href="#">Cats</a></li>
              <li><a href="#">Birds</a></li>
              <li><a href="#">Small Animal</a></li>
              <li><a href="#" class="highlight">Pharmacy</a></li>
              <li><a href="#">Online Vet Consult</a></li>
              <li><a href="#">Adoption</a></li>
            </ul>
          </div>

          <div class="col-6 col-md-3">
            <h6>QUICK LINKS</h6>

            <ul>
              <li><a href="#">About Us</a></li>
              <li><a href="#">Contact Us</a></li>
              <li><a href="#">Track Your Order</a></li>
              <li><a href="#">FAQ's</a></li>
              <li><a href="#">Returns & Exchange Policy</a></li>
              <li><a href="#">Terms Of Use</a></li>
              <li><a href="#">Privacy Policy</a></li>
            </ul>
          </div>

          <div class="col-6 col-md-3">
            <h6>EXPLORE IT</h6>

            <ul>
              <li><a href="#">Careers</a></li>
              <li><a href="#">Birthday Club</a></li>
              <li><a href="#">Learn With Wooflix</a></li>
              <li><a href="#">Customers Love</a></li>
              <li><a href="#">XXX</a></li>
              <li><a href="#">XXX</a></li>
              <li><a href="#">XXX</a></li>
            </ul>
          </div>

          <div class="col-md-3">
            <h6>DOWNLOAD WOOFLIX APP</h6>

            <div class="app-buttons">
              <img src="assets/images/google-play.png" />

              <img src="assets/images/app-store.png" />
            </div>

            <h6 class="subscribe-title">
              SUBSCRIBE FOR LATEST OFFERS AND DISCOUNTS
            </h6>

            <form class="subscribe-form">
              <div class="form-group">
                <input
                  type="email"
                  class="form-control"
                  placeholder="Enter your email"
                />
              </div>

              <button type="submit" class="btn btn-orange w-100 mt-1">Submit</button>
            </form>
          </div>
        </div>
      </div>

      @if(!empty($settings['popular_searches']))
        <div class="popular-search">
          <div class="container-xxl">
            <strong>POPULAR SEARCHES:</strong>
              @foreach(explode(',', $settings['popular_searches']) as $keyword)
                  @php $trimmed = trim($keyword); @endphp
                  
                  @if($trimmed)
                      {{-- <a href="{{ url('/search?q='.$trimmed) }}" class="popular-link"> --}}
                          {{ $trimmed }}
                      {{-- </a> --}}

                      {{-- Add the pipe separator only if it's NOT the last item --}}
                      @if (!$loop->last)
                          <span>|</span>
                      @endif
                  @endif
              @endforeach
          </div>
        </div>
      @endif

      <div class="footer-bottom">
        <div
          class="container-xxl d-flex justify-content-between align-items-center flex-wrap"
        >
          <p>© 2024, VKY TECHNOLOGIES. ALL RIGHTS RESERVED.</p>

          <div class="social-icons">
            <a href="#">YouTube</a> <a href="#">Twitter</a>
            <a href="#">LinkedIn</a> <a href="#">Facebook</a>
            <a href="#">Instagram</a>
          </div>
        </div>
      </div>

      <div class="footer-seo">
        <div class="container-xxl">
          {!! $settings['footer_about'] ?? '' !!}
        </div>
      </div>
    </footer>