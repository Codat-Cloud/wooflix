    <!-- USP Section -->
    <section class="usp-section">
      <div class="container-xxl">
        <div class="row text-center g-3">
          <div class="col-12 col-lg">
            <div class="usp-item">
              <!-- FREE SHIPPING -->
              <svg xmlns="http://www.w3.org/2000/svg"
                  width="32"
                  height="32"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">

                  <rect x="1" y="3" width="15" height="13"></rect>

                  <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>

                  <circle cx="5.5" cy="18.5" r="2.5"></circle>

                  <circle cx="18.5" cy="18.5" r="2.5"></circle>

              </svg>

              <h6>FREE SHIPPING</h6>

              <p>On Orders Above ₹699</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
              <!-- FREE RETURNS -->
              <svg xmlns="http://www.w3.org/2000/svg"
                  width="32"
                  height="32"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">

                  <polyline points="1 4 1 10 7 10"></polyline>

                  <path d="M3.51 15a9 9 0 1 0 .49-9"></path>

              </svg>

              <h6>FREE RETURNS</h6>

              <p>Within 7 days (T&C Apply)</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
            <!-- SECURE PAYMENT -->
            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <rect
                    x="2"
                    y="5"
                    width="20"
                    height="14"
                    rx="2"
                    ry="2"
                ></rect>

                <path d="M2 10h20"></path>

                <path d="M7 15h2"></path>

                <path d="M11 15h2"></path>

                <path d="M18 7l2 2-2 2"></path>

            </svg>

              <h6>SECURE PAYMENT</h6>

              <p>Your Transaction is Secure</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
            <!-- BEST SUPPORT -->
            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <path d="M18 8a6 6 0 0 0-12 0v5a2 2 0 0 0 2 2h1v-5H6"></path>

                <path d="M18 15h1a2 2 0 0 0 2-2V8a10 10 0 0 0-20 0v5a2 2 0 0 0 2 2h1"></path>

                <path d="M9 19a3 3 0 0 0 6 0"></path>

            </svg>

              <h6>BEST SUPPORT</h6>

              <p>Mon - Fri. 9 AM to 9 PM</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
            <!-- FAST DELIVERY -->
            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <path d="M5 17h14"></path>

                <path d="M5 12h10"></path>

                <path d="M5 7h6"></path>

                <path d="M19 7l-4 5h3l-1 5 4-6h-3z"></path>

            </svg>

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
              <li><a href="https://www.shiprocket.in/shipment-tracking">Track Your Order</a></li>
            <ul>
                @foreach($footerPages as $p)
                    <li>
                        <a href="{{ route('front.page', $p->slug) }}">
                            {{ $p->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
            </ul>
          </div>

          <div class="col-6 col-md-3">
            <h6>EXPLORE IT</h6>

            <ul>
              <li><a href="#">Careers</a></li>
              <li><a href="#">Birthday Club</a></li>
              <li><a href="#">Learn With Wooflix</a></li>
              <li><a href="#">Customers Love</a></li>
            </ul>
          </div>

          <div class="col-md-3">
            {{-- <h6>DOWNLOAD WOOFLIX APP</h6>

            <div class="app-buttons">
              <img src="assets/images/google-play.png" />

              <img src="assets/images/app-store.png" />
            </div> --}}

            <h6 class="subscribe-title mt-0">
              SUBSCRIBE FOR LATEST OFFERS AND DISCOUNTS
            </h6>

            @livewire('front.newsletter-form')
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
                      <a href="{{ url('/collections?q='.$trimmed) }}" class="popular-link text-decoration-none text-dark">
                          {{ $trimmed }}
                      </a>

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
                @if(!empty($socialLinks['facebook']))
                    <a href="{{ $socialLinks['facebook'] }}" target="_blank" title="Facebook">Facebook</a>
                @endif

                @if(!empty($socialLinks['instagram']))
                    <a href="{{ $socialLinks['instagram'] }}" target="_blank" title="Instagram">Instagram</a>
                @endif

                @if(!empty($socialLinks['youtube']))
                    <a href="{{ $socialLinks['youtube'] }}" target="_blank" title="YouTube">YouTube</a>
                @endif

                @if(!empty($socialLinks['linkedin']))
                    <a href="{{ $socialLinks['linkedin'] }}" target="_blank" title="LinkedIn">LinkedIn</a>
                @endif

                @if(!empty($socialLinks['twitter']))
                    <a href="{{ $socialLinks['twitter'] }}" target="_blank" title="Twitter">Twitter</a>
                @endif

                @if(!empty($socialLinks['pinterest']))
                    <a href="{{ $socialLinks['pinterest'] }}" target="_blank" title="Pinterest">Pinterest</a>
                @endif
            </div>

        </div>
      </div>

      <div class="footer-seo">
        <div class="container-xxl">
          {!! $settings['footer_about'] ?? '' !!}
        </div>
      </div>
    </footer>